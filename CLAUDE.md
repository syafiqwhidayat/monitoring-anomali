# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Sidik Anomali — a CodeIgniter 4 app for BPS (Badan Pusat Statistik) field officers to detect, assign, and confirm statistical anomalies ("anomali") during census activities (SE = Sensus Ekonomi 2026) across Sumatera Barat (`1300`) and its kabupaten/kecamatan/desa/SLS/sub-SLS hierarchy.

**All UI text, code comments, and domain terms are in Indonesian.** Match that style when writing code here.

Stack: PHP 8.1+ / CodeIgniter 4.7.2 / CodeIgniter Shield (auth) / codeigniter4/settings / phpoffice/phpspreadsheet / PHPUnit 10.5.
Frontend (loaded from CDN in `app/Views/layout/template.php`): Tabler UI, Bootstrap 5.3.8, Tom Select, jQuery 3.6, Chart.js (+ boxplot/matrix/sankey plugins), Leaflet 1.9.4, wordcloud2.js. Per-page JS lives in `public/js/`.

## Commands

```bash
php spark serve                  # dev server (baseURL http://localhost:8080/)
php spark routes                 # list routes
php spark list                   # list all CLI commands
composer test                    # == vendor/bin/phpunit
vendor/bin/phpunit                                          # whole suite
vendor/bin/phpunit --filter HealthTest                      # single test
vendor/bin/phpunit tests/unit/HealthTest.php                # single file
```

Composer is vendored as `composer.phar` at the repo root (`php composer.phar install`).

### Tests are stock CI4 stubs

`tests/` contains only the CI4 app-starter examples (`HealthTest`, `ExampleSessionTest`, `ExampleDatabaseTest`) — **no real application tests exist**. Do not assume a failing suite means a regression; check whether it is one of the stub files first.

`system/Test/bootstrap.php` forces `ENVIRONMENT = 'testing'`, which makes `app/Config/Database.php` swap the connection to its `tests` group (SQLite3 `:memory:`). So `ExampleDatabaseTest` needs the **sqlite3 PHP extension** and errors out with *"required PHP extension sqlite3 is not loaded"* without it. The `database.tests.*` keys in `.env` and `phpunit.xml.dist` are commented out and unused — the in-memory SQLite group is what actually applies.

### Database

MySQL (`monitoring_anomali`), `DBPrefix` empty, `DBDebug = true`, `strictOn = false`, connection in `.env` (gitignored). Note there is no CI4 `env` template file in the repo — `env copy` is a byte-identical committed copy of `.env` (see Gotchas).

**`app/Database/Migrations/` contains only `.gitkeep`.** Schema is managed by the `monitoring_anomali.sql` dump at the repo root — `php spark migrate` will not build this app. `app/Database/Forge/forge.php` is a one-off bootstrap script for creating the database and the `users` table, not part of the request flow.

**The SQL dump is a stale snapshot.** It holds 22 tables and predates much of the code. Tables that exist in the live DB but are *absent from the dump* include `se_mon_ngibar`, `se_cekduplikat`, `se_list_ngibar`, `se_list_se26_ub`, `se_mon_ub`, `se_progres_subsls`, `se_bangunan`, `identifikasi_anades`, `identifikasi_kategori`, `kesimpulan_anomali`, `knowledge_base`, `laporan_diskusi`, `laporan_kendala`, `rt_art`, `upload_logs`, `wilayah_geojson`, plus the view `v_anomali_kegiatan_wilayah_tugas`. Don't assume the dump is the current schema — check the live DB.

`app/Database/Seeds/` does contain 8 real seeders (`AnomaliSeeder`, `KategoriAnomaliSeeder`, `KegiatanSeeder`, `RtArtSeeder`, `UserSeeder`, `WilayahSeeder`, `BroadcstSeeder`, `MonitoringAnomaliSeeder`).

### Background jobs (spark commands)

Uploaded Excel files are never processed inline. The controller writes a queue row to `log_upload` (status `pending`) and the work is handed to a spark command — either spawned directly on Windows or drained by a cron worker.

```bash
php spark proses:semua            # queue worker (cron) — dispatches pending log_upload rows
php spark proses:anomali <namaFile> <logId> <id_kegiatan> <level_anomali>
php spark proses:anomali_individu <namaFile> <logId> <id_kegiatan> <idKab> <idUser> [forced]
php spark proses:konfirmasi <namaFile> <logId> <idKab>
php spark proses:wilayah <namaFile> <logId> <id_kegiatan> <wilayahKerja>
php spark ai:rangkum               # Gemini-backed summariser; needs gemini.apiKey / gemini.model in .env
```

`app/Commands/JobRunner.php` (`proses:semua`) holds the anti-overlap lock: it refuses to start if any `log_upload` row is still `status = 'proses'`, and marks jobs stuck >15 min as `gagal` (the code comment says 10 minutes — the code uses 15). It maps `log_upload.jenis` to a command name.

**How a job actually starts.** The web controllers (`Anom::store`, `ManajAnom::store`, `Api\AnomaliController::storeFromApi`) insert a `pending` `log_upload` row and then spawn a **Windows-specific detached process**:

```php
$command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' > NUL 2>&1"';
\pclose(\popen($command, "r"));
```

The Linux variant is commented out. So uploads only auto-process on Windows, and `proses:semua` is the cron-based fallback path.

**There is no `app/Config/Commands.php`**, so CI4's built-in scheduler is not configured — cron is an external OS crontab calling `php spark proses:semua` (and `php spark ai:rangkum`). No crontab file is committed.

Per-command behaviour worth knowing: `proses:anomali` groups Excel rows by kabupaten and wraps each kabupaten in its **own transaction**, auto-creating missing `kategori_anomali` and `assigment` rows, and uses `is_insert` as a "present in the latest upload" flag (rows that vanish get `is_sistem = 1`). `proses:konfirmasi` reads only columns A–G via a custom `ChunkReadFilter` and stops after two consecutive empty 1000-row chunks ("ghost row" protection). `proses:wilayah` provisions users from the Excel (columns G/H/I = PPL/PML/Koseka emails) and assigns `operator` to `@bps.go.id` addresses and `mitra` to everyone else — with a **hardcoded default password** for the accounts it creates.

## Architecture

### Routing — everything is explicit

`app/Config/Routes.php:11` sets `setAutoRoute(false)`. **Auto-routing is off**: a controller method is unreachable until you add a route. No route names are used — link by literal path (`base_url('anomali/listDetil')`).

URL prefixes map to feature modules: `/anomali`, `/manajemen-anomali`, `/monitoring*`, `/identifikasi`, `/se`, `/peta`, `/user`, `/wilayah`, `/admin`, `/fasihhelpdesk`, `/broadcast`, `/api`. Shield's own routes are pulled in by `service('auth')->routes($routes)`.

A single prefix is often split across **two route groups with different role allow-lists** (e.g. `anomali` at lines 25 and 37, `manajemen-anomali` at 46 and 49). When adding a route to such a prefix, decide which group it belongs to — the more privileged one.

### Auth & role checking — read this before touching access control

- **Shield groups** (`app/Config/AuthGroups.php`): `superadmin`, `admin`, `operator`, `mitra` (default group `user`). Login is **email + password** (`Auth::$validFields = ['email']`); custom login view at `app/Views/auth/login_custom.php`.
- **Authorization is NOT the permission matrix.** `$matrix` / `$permissions` in `AuthGroups.php` are never consulted in code. Access is enforced by the `activeRole` filter alias (`app/Filters/ActiveRoleFilter.php`), which reads the **session key `aktif_role`** and checks it against the role list declared on the route:
  ```php
  $routes->group('se', ['filter' => 'activeRole:superadmin,admin,operator'], ...);
  ```
- `aktif_role` is set at login (`app/Config/Events.php`) to the user's **first** Shield group, and can be changed at runtime via `GET /set_role` → `Admin\UserController::gantiRole()`. A user with several groups therefore re-scopes their own permissions by switching role.
- `pml` / `ppl` are **not** Shield groups — they are organisational positions stored in the app's own tables (`wilayah_tugas`, `assigment`) and surfaced as data.
- **`apiKeyAuth`** (`app/Filters/ApiKeyFilter.php`) guards the whole `/api` group: the request must carry header `X-API-KEY` equal to `env('API_SECRET_KEY')`. `/api` is also excluded from the global `session` filter, so Shield's session authenticator is unavailable there. `API_SECRET_KEY` is **not** in `.env` by default — every `/api/*` request returns 401 until you set it.

### Session bootstrap

`app/Controllers/BaseController.php::initController()` (every controller extends it) initialises, on first request after login: `aktif_role`, `aktif_kegiatan`, `nama_kegiatan`, `is_rt`, `level_wilayah`, `isOrganik`. `isOrganik` is derived from the identity email ending in `@bps.go.id` (BPS staff) vs. `mitra` (contracted partner, whose kegiatan list comes from `WilayahTugasModel::getKegiatanByUser()`).

### Layers

`Controller → Model → View`. Controllers are large and hold most business logic (`app/Controllers/SeMonitoring.php` ~82 KB, `Anom.php` ~54 KB); reporting queries live in `app/Models/*.php` as query-builder joins, e.g. `AnomaliModel::getAnomaliByWilayah()`.

Model → table (`protected $table`):

| Model | Table |
|---|---|
| `AnomaliModel` | `anomali` — core anomaly records + the aggregate/reporting queries (`jumlahKonfirmasiByPublik`, `getTop5`, `wordCloudKonfirmasi`, …) |
| `KatAnomaliModel` | `kategori_anomali` — anomaly **category catalogue** (unique per kegiatan) |
| `MonitoringModel` | `kategori_anomali` too — a confusingly-named near-duplicate of `KatAnomaliModel`; it is a category lookup, **not** a monitoring store |
| `AssigmentModel` | `assigment` — RT/ART/NRT survey assignment master (`getOrInsert()` upsert) |
| `WilayahModel` / `WilayahTugasModel` | `wilayah` (prov→kab→kec→des→sls→subsls) / `wilayah_tugas` (PPL/PML/Koseka ↔ wilayah per kegiatan) |
| `KegiatanModel` | `kegiatan` — survey activities; `is_rt` + `level_wilayah` drive the hierarchy logic |
| `LogUploadModel` | `log_upload` — upload job queue for the **anomaly** domain (status pending/proses/selesai/gagal) |
| `SeLogModel` | `se_upload_log` — the **SE2026** twin of `log_upload` |
| `BroadcastModel` | `broadcasts` |
| `IdenAnadesModel` / `IdenKategorikModel` | `identifikasi_anades` (numeric outliers) / `identifikasi_kategori` (categorical misallocation) |
| `Se*Model` | the **Sensus Ekonomi 2026** module: `se_monitoring`, `se_mon_ngibar`, `se_list_ngibar`, `se_cekduplikat` |
| `AnomaliKegiatanWilayahModel` | `v_anomali_kegiatan_wilayah_tugas` (a DB view; the file's class is `AnomaliKegiatanWilayahTugasModel`, and the call site in `Anom::list()` sits behind `if (true)`, so the path is dead) |
| `UserModel` | `users` — extends `CodeIgniter\Shield\Models\UserModel` |

The `Se` prefix means **Sensus Ekonomi (SE2026)**, not "search" or "server-side". Models without the prefix are the app's own anomaly domain; `se_*` tables are landing/snapshot tables fed by Fasih/SE exports and operator uploads.

**The newest SE tables have no model at all** — `se_progres_subsls`, `se_list_se26_ub` and `se_mon_ub` are queried raw via `$this->db->table(...)` inside `app/Controllers/SeMonitoring.php` and the `Api\Se*` controllers.

### Users — one table, not two

There is **no separate app user table**. Shield's `users` table is extended in place: `app/Entities/User.php` extends Shield's entity (intentionally empty) and `app/Models/UserModel.php` appends two columns to `$allowedFields` — `name` and `wilayah_kerja` (the BPS wilayah code). `Auth::$userModel` points Shield at this model.

Emails and passwords live in `auth_identities` (`type = 'email_password'`), roles in `auth_groups_users`. Every user listing therefore joins all three:

```php
$this->select('users.id, users.name as nama, auth_identities.secret as email, users.wilayah_kerja, auth_groups_users.group AS role')
    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
    ->join('auth_identities', 'auth_identities.user_id = users.id')
    ->where('auth_identities.type', 'email_password');
```

Domain rule used throughout: **organik** (BPS staff) = email `LIKE '%@bps.go.id'` and group ≠ `mitra`; everyone else is a partner.

### Reporting-query conventions

- Breakdown counting is always `SUM(CASE WHEN … THEN 1 ELSE 0 END) as jumlah_x` (terisi / public / non-public / flag1-3 / is_lap).
- Wilayah rollups use `LEFT(id_wilayah, n)` to collapse prov→kab→kec→des→sls.
- "Latest snapshot per wilayah" is done with a compiled subquery joined back onto the base table:
  ```php
  $sub = $this->builder()->select('kd_wilayah, MAX(created_at) as terbaru')->groupBy('kd_wilayah')->getCompiledSelect();
  $this->select('se_monitoring.kd_wilayah')->join("($sub) t", 'se_monitoring.kd_wilayah = t.kd_wilayah AND se_monitoring.created_at = t.terbaru');
  ```
- The progress dashboard self-left-joins `se_progres_subsls` on the previous date to produce `*_delta` columns, then aggregates `GROUP BY w.id_ppl`.

### Exports are written as PHP scripts under `app/Views/`

The xlsx export "views" are **not templates** — they are PHP scripts that build a PhpSpreadsheet workbook, set the download headers, `$writer->save('php://output')` and `exit`. They are still invoked through the view loader, so they receive `$data` the usual way:

```php
return view('anomali/excelRekapAnom', $data);   // app/Controllers/Anom.php:720
```

The builders live at `app/Views/anomali/excelRekapAnom.php` (Rekap Assignment export), `excelKonfirFasih.php`, and `excelRekapTemplate.php` (blank confirmation template with the input columns F:G highlighted, which `proses:konfirmasi` reads back). `Anom::rekapAnomali()` switches between them on `?export=excel` / `?export=template`.

Conventions inside them: IDs and wilayah codes are written with `DataType::TYPE_STRING` / `FORMAT_TEXT` so leading zeros survive; styling is applied in bulk (per-row styling is too slow) and column widths are set statically instead of `autoSize` for CPU reasons.

Static import templates (blank xlsx for users to fill in) live in `public/assets/templates/` — `template_anomali.xlsx`, `template_monitoring.xlsx`, `template_se_mon_ngibar.xlsx`, `template_wilayah_tugas.xlsx`.

### Views & filters

The shell is `app/Views/layout/template.php`: `<div class="page">` → `sidebar.php` + `page-wrapper` → `header.php` → `page-body` → `$this->renderSection('content')`. Child views use `<?= $this->extend('layout/template') ?>` and `<?= $this->section('content') ?>`.

- `layout/footer.php` is **orphaned** — nothing includes it. It carries the Bootstrap 5.3.8 JS bundle, so dropdowns/collapse actually come from Tabler's bundled JS.
- `layout/navbar.php` is a separate horizontal bar that the shell also doesn't include.
- Reusable header widgets are **CI4 Cells** rendered with `view_cell('App\Cells\KegiatanCell::dropdown')` (also `App\Cells\UserCell`). `KegiatanCell::dropdown()` picks the kegiatan list by role — `WilayahTugasModel::getKegiatanByUser()` for `mitra`, otherwise `KegiatanModel::getKegiatanDesc()` — and auto-selects the first one into the session.
- The sidebar does its own RBAC inline, e.g. `session('aktif_role') !== 'mitra'` and `in_array(session('aktif_role'), ['superadmin','admin'])`, plus `url_is('anomali*')` for the active state. The route filter is not the only gate.
- **There is no DataTables.** Tables are hand-rolled with `Config\Services::pager()->makeLinks($page, $perPage, $totalRows, 'my_pager', 0, 'group_assignment')` and the `app/Views/pager/` templates.
- **jQuery is used in only 3 views** (`identifikasi/anades.php`, `identifikasi/kategorik.php`, `peta/index.php`); everything else is vanilla JS in `public/js/` (`script.js`, `scriptDaftarAnomali.js`, `scriptManajemenAnomali.js`, `scriptMonitoringAnomali.js`, `scriptWilayahTugas.js`, `broadcast.js`, `monitoring.js`).
- `public/css/style.css` holds the BPS theme (CSS vars `--biru-bps`, `--oren-bps`, `--hijau-bps`, `--sidik-prm`, `--tblr-primary`).

List filters travel as GET params prefixed `fil-` (`fil-wilayah`, `fil-level`, `fil-kategori`, `fil-flag`, `fil-stat`) and are read with `$this->request->getGet('fil-...')` in the controller, then passed to the view as `$data['filterWilayah']` etc. Detail/accordion fragments are fetched over AJAX (e.g. `Anom::listDetil` returns a partial).

### API layer

`app/Controllers/Api/*` is JSON-only and uses `CodeIgniter\API\ResponseTrait` (`$this->respond()`, `$this->fail()`, `$this->respondCreated()`). `Api\AnomaliController::storeFromApi` additionally authenticates the *end user* inline with Shield (`auth()->check(['email'=>..., 'password'=>...])`) so the upload can be attributed to an `id_user`.

## Gotchas

- **CSRF is disabled app-wide** — `'csrf'` is commented out of `globals.before` in `app/Config/Filters.php` and no route enables it. Every POST (uploads, deletes, role changes) is unprotected. Don't assume CSRF is on.
- `codeigniter4/restful` is **not** in `composer.json` / `composer.lock` / `vendor`, yet `Api\SeListUbController` and `Api\SeProgresController` extend `CodeIgniter\RESTful\ResourceController`. Those two endpoints fatal in this checkout — either install the package or convert them to `ResponseTrait`.
- `GET /profile` and `POST /profile/update` (`app/Config/Routes.php:189-190`) carry **no** `activeRole` filter — they rely on `auth()->loggedIn()` inside `Admin\UserController`.
- The `/admin` group uses `activeRole:admin` only, so `superadmin` is **blocked** from `/admin/users`.
- `Shield::$allowRegistration = true` and `/register` is public — anyone can create an account (it lands in group `user`, which passes no `activeRole` filter).
- Some controllers call `redirect()->to('')` with an empty target — a latent redirect bug, don't copy that pattern.
- `app/Controllers/Comics.php`, `Pages.php`, and `Upload.php` are CI4-tutorial leftovers with their routes commented out — dead code.
- `app/Config/App.php` has `baseURL = 'http://localhost:8080/'` hardcoded; `.env` overrides it per environment.
- `builds` at the repo root is the **stock CI4 stability-toggle script** (`php builds [release|development]`), not a build or deploy pipeline. There is no package.json, no bundler, no Dockerfile.
- `app/Common.php` is the **untouched CI4 stub** — zero helper functions. `app/Helpers/` holds only `.gitkeep`. The globally autoloaded helpers (`app/Config/Autoload.php`) are the vendor ones: `auth` (Shield) and `setting`.
- `app/Validation/CustomRules.php` defines a `uniqueWith` rule, but `\App\Validation\CustomRules::class` is **commented out of `$ruleSets`** — the rule is not registered and is effectively dead code.

### Known bugs worth not propagating

- `SeMonitoringModel::$allowedFields` contains **`tbh_opeh`** (typo) while the real column is `tbh_open`. With `$protectFields = true`, that value is silently dropped on insert.
- `app/Controllers/Api/AnomaliController.php` error path writes to a table named **`upload_logs`**, but every other component uses `log_upload` — a table that doesn't exist.
- `app/Views/layout/cells/dropdown_role.php` still labels itself **"Kegiatan :"** (copy-paste from the kegiatan dropdown).
- `app/Commands/ProsesWilayah.php` has stray `use SebastianBergmann\Environment\Console;` and `use function PHPUnit\Framework\isNull;` imports (copy-paste leftovers, harmless).
- The repo root contains **`env copy`**, a plaintext copy of `.env` with credentials, committed to version control. `.env` itself is gitignored.
- `ai:rangkum` sends field officers' confirmation text to the external Google Gemini API and stores the returned summary in `kesimpulan_anomali`. Treat that table's contents as leaving the organisation.
