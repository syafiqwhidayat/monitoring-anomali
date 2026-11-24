<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<!-- Resource CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    #map-container {
        position: relative;
        width: 100%;
        height: calc(100vh - 270px);
        min-height: 500px;
        border-radius: 8px;
        overflow: hidden;
    }

    #map {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    /* Tombol layer: warna tombol = warna stroke poligon layer tersebut */
    .btn-layer[aria-pressed="true"] {
        color: #fff;
        border-color: transparent;
    }

    .btn-layer[data-level="subsls"][aria-pressed="true"] {
        background-color: #206bc4;
    }

    .btn-layer[data-level="sls"][aria-pressed="true"] {
        background-color: #f76707;
    }

    .btn-layer[data-level="desa"][aria-pressed="true"] {
        background-color: #1e7960;
    }

    .btn-layer[data-level="kecamatan"][aria-pressed="true"] {
        background-color: #8b5cf6;
    }

    .btn-layer[data-level="subsls"][aria-pressed="false"] {
        color: #206bc4;
        border-color: #206bc4;
    }

    .btn-layer[data-level="sls"][aria-pressed="false"] {
        color: #f76707;
        border-color: #f76707;
    }

    .btn-layer[data-level="desa"][aria-pressed="false"] {
        color: #1e7960;
        border-color: #1e7960;
    }

    .btn-layer[data-level="kecamatan"][aria-pressed="false"] {
        color: #8b5cf6;
        border-color: #8b5cf6;
    }

    #hasil-lokasi {
        font-variant-numeric: tabular-nums;
    }
</style>

<div class="container-fluid">
    <!-- Header Judul -->
    <div class="card card-body mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2 m-0">Peta Spasial SLS & Titik Bangunan</h1>
            <div class="d-flex gap-2">
                <!-- Tombol Lokasi Saya -->
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLokasiSaya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-current-location" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M12 12m-8 0a8 8 0 1 0 16 0a8 8 0 1 0 -16 0" />
                        <path d="M12 2l0 2" />
                        <path d="M12 20l0 2" />
                        <path d="M20 12l2 0" />
                        <path d="M2 12l2 0" />
                    </svg>
                    Lokasi Saya
                </button>

                <?php if (auth()->user()->inGroup('superadmin', 'admin')): ?>
                    <a href="<?= base_url('peta/upload'); ?>" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 17v2a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-2" />
                            <path d="M7 9l5-5l5 5" />
                            <path d="M12 4v12" />
                        </svg>
                        Upload Data Peta
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('error'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('message'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Horizontal Card Filter Wilayah (Di atas Peta) -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label required mb-1 small fw-bold">Kabupaten/Kota</label>
                    <select id="sel-kab" class="form-select form-select-sm">
                        <option value="">-- Memuat... --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label required mb-1 small fw-bold">Kecamatan</label>
                    <select id="sel-kec" class="form-select form-select-sm" disabled>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label required mb-1 small fw-bold">Desa/Kelurahan</label>
                    <select id="sel-des" class="form-select form-select-sm" disabled>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label required mb-1 small fw-bold">SLS</label>
                    <select id="sel-sls" class="form-select form-select-sm" disabled>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label required mb-1 small fw-bold">Sub SLS</label>
                    <select id="sel-subsls" class="form-select form-select-sm" disabled>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm w-100" id="btnTampilkanPeta" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 18l-2 -1l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                            <path d="M9 4v13" />
                            <path d="M15 7v5" />
                            <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M20.2 20.2l1.8 1.8" />
                        </svg>
                        Tampilkan Peta
                    </button>
                </div>
            </div>

            <!-- Baris kedua: tombol layer batas wilayah -->
            <div class="row g-2 align-items-center mt-2 pt-2 border-top">
                <div class="col-md-3">
                    <label class="form-label mb-1 small fw-bold">Tampilkan Batas</label>
                </div>
                <div class="col-md-9">
                    <div class="btn-group btn-group-sm w-100" role="group" aria-label="Pilih level batas wilayah">
                        <button type="button" class="btn btn-outline-secondary btn-layer w-100" data-level="subsls" aria-pressed="true">Sub SLS</button>
                        <button type="button" class="btn btn-outline-secondary btn-layer w-100" data-level="sls" aria-pressed="false">SLS</button>
                        <button type="button" class="btn btn-outline-secondary btn-layer w-100" data-level="desa" aria-pressed="false">Desa</button>
                        <button type="button" class="btn btn-outline-secondary btn-layer w-100" data-level="kecamatan" aria-pressed="false">Kecamatan</button>
                    </div>
                    <div class="form-text mt-1">
                        Klik langsung di peta untuk melihat batas wilayah titik tersebut, atau tekan
                        <strong>Lokasi Saya</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Hasil Identifikasi -->
    <div id="panel-hasil" class="card mb-3 d-none">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="fw-bold text-primary fs-6" id="hasil-nama">—</div>
                    <div class="small text-muted" id="hasil-wilayah">—</div>
                </div>
                <div class="col-md-2">
                    <div class="small text-muted">Jumlah Titik Bangunan</div>
                    <div class="fw-bold" id="hasil-bangunan">—</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Koordinat</div>
                    <div class="fw-bold small" id="hasil-lokasi">—</div>
                </div>
                <div class="col-md-1">
                    <div class="small text-muted">Sumber</div>
                    <div class="fw-bold small" id="hasil-sumber">—</div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="btnResetPeta">Reset Peta</button>
                </div>
            </div>
            <div id="hasil-pesan" class="alert alert-warning py-1 px-2 small mt-2 mb-0 d-none"></div>
        </div>
    </div>

    <!-- Container Peta Full Width -->
    <div id="map-container" class="card shadow-sm border-0">
        <div id="map"></div>
    </div>
</div>

<!-- JS Leaflet & Dependency -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    $(document).ready(function() {

        // 1. Inisialisasi Leaflet Map
        const map = L.map('map', {
            zoomControl: false
        }).setView([-0.9471, 100.4172], 9);

        L.control.zoom({
            position: 'topleft'
        }).addTo(map);

        // 2. Tile Layer: Google Maps Hybrid
        L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Maps'
        }).addTo(map);

        const BASE = '<?= rtrim(base_url(), '/'); ?>';

        // Warna layer: warna tombol = warna stroke poligon
        const WARNA = {
            subsls: { fill: '#206bc4', stroke: '#0054a6' },
            sls: { fill: '#f76707', stroke: '#f76707' },
            desa: { fill: '#1e7960', stroke: '#1e7960' },
            kecamatan: { fill: '#8b5cf6', stroke: '#8b5cf6' }
        };

        const LAYER = {
            subsls: L.layerGroup().addTo(map),
            sls: L.layerGroup(),
            desa: L.layerGroup(),
            kecamatan: L.layerGroup()
        };

        const layerMarker = L.layerGroup().addTo(map);
        const layerPengguna = L.layerGroup().addTo(map);

        // Layer yang sedang ditampilkan
        const levelAktif = {
            subsls: true,
            sls: false,
            desa: false,
            kecamatan: false
        };

        // Cache outline parent di sisi klien (satu outline = satu request)
        const cacheBatas = {};

        let infoTerakhir = null;

        // Helper untuk mendapatkan instance Tom Select dari elemen Selector
        function getTS(selector) {
            const el = document.querySelector(selector);
            if (el && el.tomselect) {
                return el.tomselect;
            }
            if (typeof TomSelect !== 'undefined' && el) {
                return new TomSelect(selector, {
                    create: false
                });
            }
            return null;
        }

        // Helper untuk memperbarui opsi pada Tom Select
        function updateTomSelect(selector, data, placeholder, valueKey, labelKey) {
            const ts = getTS(selector);
            if (!ts) return;

            ts.clear();
            ts.clearOptions();

            if (Array.isArray(data) && data.length > 0) {
                const options = data.map(item => {
                    const val = item[valueKey] || item.id || '';
                    const lbl = item[labelKey] || val;
                    return {
                        value: String(val).trim(),
                        text: `[${val}] ${lbl}`
                    };
                });
                ts.addOptions(options);
                ts.enable();
            } else {
                ts.disable();
            }
            ts.refreshOptions(false);
        }

        // Helper untuk mereset berantai Tom Select berikutnya
        function resetTomSelects(selectors) {
            selectors.forEach(s => {
                const ts = getTS(s);
                if (ts) {
                    ts.clear();
                    ts.clearOptions();
                    ts.disable();
                }
            });
            $('#btnTampilkanPeta').prop('disabled', true);
        }

        // 3. Load Kabupaten via AJAX
        $.getJSON(BASE + '/peta/get-kabupaten')
            .done(function(data) {
                const tsKab = getTS('#sel-kab');
                if (tsKab && Array.isArray(data)) {
                    tsKab.clear();
                    tsKab.clearOptions();
                    const options = data.map(item => ({
                        value: String(item.kd_kab).trim(),
                        text: `[${item.kd_kab}] ${item.nm_kab}`
                    }));
                    tsKab.addOptions(options);
                    tsKab.enable();
                    tsKab.refreshOptions(false);
                }
            })
            .fail(function(jqxhr, textStatus, error) {
                console.error("Gagal memuat kabupaten:", textStatus, error);
            });

        // 4. Cascading Event Handlers Menggunakan Event Tom Select ('change')

        // Change Kabupaten -> Load Kecamatan
        const tsKab = getTS('#sel-kab');
        if (tsKab) {
            tsKab.on('change', function(kab) {
                resetTomSelects(['#sel-kec', '#sel-des', '#sel-sls', '#sel-subsls']);
                if (!kab) return;

                $.getJSON(BASE + '/peta/get-kecamatan', {
                    kd_kab: kab
                }, function(data) {
                    updateTomSelect('#sel-kec', data, '-- Pilih --', 'kd_kec', 'nm_kec');
                });
            });
        }

        // Change Kecamatan -> Load Desa
        const tsKec = getTS('#sel-kec');
        if (tsKec) {
            tsKec.on('change', function(kec) {
                resetTomSelects(['#sel-des', '#sel-sls', '#sel-subsls']);
                const kab = $('#sel-kab').val();
                if (!kec || !kab) return;

                $.getJSON(BASE + '/peta/get-desa', {
                    kd_kab: kab,
                    kd_kec: kec
                }, function(data) {
                    updateTomSelect('#sel-des', data, '-- Pilih --', 'kd_des', 'nm_des');
                });
            });
        }

        // Change Desa -> Load SLS
        const tsDes = getTS('#sel-des');
        if (tsDes) {
            tsDes.on('change', function(des) {
                resetTomSelects(['#sel-sls', '#sel-subsls']);
                const kab = $('#sel-kab').val();
                const kec = $('#sel-kec').val();
                if (!des || !kec || !kab) return;

                $.getJSON(BASE + '/peta/get-sls', {
                    kd_kab: kab,
                    kd_kec: kec,
                    kd_des: des
                }, function(data) {
                    updateTomSelect('#sel-sls', data, '-- Pilih --', 'kd_sls', 'nm_sls');
                });
            });
        }

        // Change SLS -> Load Sub SLS
        const tsSls = getTS('#sel-sls');
        if (tsSls) {
            tsSls.on('change', function(sls) {
                resetTomSelects(['#sel-subsls']);
                const kab = $('#sel-kab').val();
                const kec = $('#sel-kec').val();
                const des = $('#sel-des').val();
                if (!sls || !des || !kec || !kab) return;

                $.getJSON(BASE + '/peta/get-subsls', {
                    kd_kab: kab,
                    kd_kec: kec,
                    kd_des: des,
                    kd_sls: sls
                }, function(data) {
                    const tsSubSls = getTS('#sel-subsls');
                    if (!tsSubSls) return;

                    tsSubSls.clear();
                    tsSubSls.clearOptions();

                    if (Array.isArray(data) && data.length > 0) {
                        const options = data.map(item => {
                            const idSubSls = item.idsubsls || item.id_subsls || item.id;
                            const kdSubSls = item.kd_subsls || idSubSls;
                            // Nama SLS dipakai sebagai label (kolom nm_subsls tidak ada di tabel wilayah).
                            return {
                                value: String(idSubSls).trim(),
                                text: `[${kdSubSls}] ${item.nm_sls || kdSubSls}`
                            };
                        });
                        tsSubSls.addOptions(options);
                        tsSubSls.enable();
                    } else {
                        tsSubSls.disable();
                    }
                    tsSubSls.refreshOptions(false);
                });
            });
        }

        // Change Sub SLS -> Toggle Tombol Tampilkan Peta
        const tsSubSls = getTS('#sel-subsls');
        if (tsSubSls) {
            tsSubSls.on('change', function(val) {
                $('#btnTampilkanPeta').prop('disabled', !val);
            });
        }

        // =====================================================================
        // 5. FUNGSI GAMBAR & PANEL HASIL
        // =====================================================================

        function styleLevel(level) {
            const w = WARNA[level] || WARNA.subsls;
            return {
                color: w.stroke,
                weight: 3,
                opacity: 0.9,
                fillColor: w.fill,
                fillOpacity: 0.2
            };
        }

        function gambarPoligon(layer, geojson, level) {
            layer.clearLayers();
            if (!geojson || !geojson.features || !geojson.features.length) {
                return null;
            }
            return L.geoJSON(geojson, { style: styleLevel(level) }).addTo(layer);
        }

        function gambarBangunan(list) {
            layerMarker.clearLayers();
            if (!Array.isArray(list)) return;

            list.forEach(b => {
                const lat = parseFloat(b.latitude);
                const lng = parseFloat(b.longitude);
                // Buang koordinat kosong (0,0) - data import bisa tanpa geotag.
                if (!isFinite(lat) || !isFinite(lng) || (lat === 0 && lng === 0)) return;

                const jnsBangunan = b.jns_bangunan_value ? b.jns_bangunan_value : '-';
                const marker = L.marker([lat, lng])
                    .bindPopup(`
                        <div class="p-1">
                            <div class="fw-bold mb-1 text-primary">No. Bangunan: ${b.no_bangunan}</div>
                            <div class="small mb-1"><strong>Principal:</strong> ${b.nama_principal}</div>
                            <div class="small mb-1"><strong>Jenis Bangunan:</strong> ${jnsBangunan}</div>
                            <div class="small text-muted"><strong>ID SubSLS:</strong> ${b.idsubsls}</div>
                        </div>
                    `);
                layerMarker.addLayer(marker);
            });
        }

        function kosongkanPeta() {
            layerMarker.clearLayers();
            LAYER.subsls.clearLayers();
            LAYER.sls.clearLayers();
            LAYER.desa.clearLayers();
            LAYER.kecamatan.clearLayers();
            infoTerakhir = null;
        }

        function setPesan(pesan) {
            const el = $('#hasil-pesan');
            if (!pesan) {
                el.addClass('d-none').text('');
                return;
            }
            el.removeClass('d-none').text(pesan);
        }

        function namaWilayah(info) {
            if (!info) return '—';
            const bagian = [info.nm_kab, info.nm_kec, info.nm_des].filter(Boolean).join(' / ');
            const subsls = info.kd_subsls ? ' · Sub SLS ' + info.kd_subsls : '';
            return bagian + subsls;
        }

        function isiPanel(res, pesan, lat, lng, sumber) {
            $('#panel-hasil').removeClass('d-none');

            if (res && res.info) {
                const info = res.info;
                // Pada tabel wilayah, nm_sls adalah nama wilayah terkecil (Sub SLS).
                $('#hasil-nama').text(info.nm_sls || '—');
                $('#hasil-wilayah').text(namaWilayah(info));
                $('#hasil-bangunan').text(res.jumlah_bangunan != null ? res.jumlah_bangunan : '—');
            } else {
                $('#hasil-nama').text('Tidak ditemukan');
                $('#hasil-wilayah').text('—');
                $('#hasil-bangunan').text('—');
            }

            $('#hasil-lokasi').text(lat != null && lng != null
                ? Number(lat).toFixed(6) + ', ' + Number(lng).toFixed(6)
                : '—');
            $('#hasil-sumber').text(sumber || '—');

            setPesan(pesan);
        }

        // =====================================================================
        // 6. IDENTIFIKASI DARI KLIK PETA / LOKASI SAYA
        // =====================================================================

        function identifikasiTitik(lat, lng, sumber) {
            setPesan('Sedang memuat data batas wilayah...');

            $.getJSON(BASE + '/peta/locate-subsls', { lat: lat, lng: lng })
                .done(function(res) {
                    if (!res || !res.found) {
                        kosongkanPeta();
                        isiPanel(null, (res && res.pesan) ? res.pesan : 'Titik tersebut berada di luar area yang memiliki data batas wilayah.', lat, lng, sumber);
                        return;
                    }

                    gambarPoligon(LAYER.subsls, res.geojson, 'subsls');
                    gambarBangunan(res.bangunan);
                    isiPanel(res, res.pesan, lat, lng, sumber);

                    infoTerakhir = res.info;
                    muatLevelAktif(res.info);
                })
                .fail(function(jqxhr) {
                    const pesan = (jqxhr.responseJSON && jqxhr.responseJSON.pesan)
                        ? jqxhr.responseJSON.pesan
                        : 'Gagal memuat data batas wilayah. Coba lagi.';
                    kosongkanPeta();
                    isiPanel(null, pesan, lat, lng, sumber);
                });
        }

        // Muat outline level parent sesuai tombol yang sedang aktif
        function muatLevelAktif(info) {
            if (!info) return;

            if (levelAktif.sls) {
                muatBatas('sls', info.kd_sls, info);
            }
            if (levelAktif.desa) {
                muatBatas('desa', info.kd_des, info);
            }
            if (levelAktif.kecamatan) {
                muatBatas('kecamatan', info.kd_kec, info);
            }
        }

        function muatBatas(level, kode, info) {
            if (!kode) return;

            const key = [level, info.kd_kab, info.kd_kec, info.kd_des, kode].join('|');
            const layer = LAYER[level];

            if (cacheBatas[key]) {
                gambarPoligon(layer, cacheBatas[key], level);
                return;
            }

            $.getJSON(BASE + '/peta/get-batas/' + encodeURIComponent(level) + '/' + encodeURIComponent(kode), {
                kd_kab: info.kd_kab,
                kd_kec: info.kd_kec,
                kd_des: info.kd_des
            }).done(function(res) {
                if (!res || !res.geojson || !res.geojson.features || !res.geojson.features.length) {
                    layer.clearLayers();
                    setPesan((res && res.pesan) ? res.pesan : 'Batas wilayah level ini belum tersedia.');
                    return;
                }
                cacheBatas[key] = res.geojson;
                gambarPoligon(layer, res.geojson, level);
            }).fail(function(jqxhr) {
                layer.clearLayers();
                const pesan = (jqxhr.responseJSON && jqxhr.responseJSON.pesan)
                    ? jqxhr.responseJSON.pesan
                    : 'Gagal memuat batas wilayah level ini.';
                setPesan(pesan);
            });
        }

        // Klik pada peta -> identifikasi Sub SLS
        map.on('click', function(e) {
            // Klik pada marker bangunan tidak memicu identifikasi ulang.
            if (e.originalEvent && e.originalEvent.__fromMarker) return;
            identifikasiTitik(e.latlng.lat, e.latlng.lng, 'Klik peta');
        });

        // 7. Render Map Data dari dropdown
        $('#btnTampilkanPeta').click(function() {
            const idSubSls = $('#sel-subsls').val();
            if (!idSubSls) return;

            layerMarker.clearLayers();
            LAYER.subsls.clearLayers();

            $.getJSON(BASE + '/peta/get-data-map/' + encodeURIComponent(idSubSls))
                .done(function(res) {
                    if (!res || res.status !== 'success') {
                        setPesan('Data peta tidak dapat dimuat.');
                        return;
                    }

                    const layerPoligon = gambarPoligon(LAYER.subsls, res.geojson, 'subsls');
                    gambarBangunan(res.bangunan);

                    // fitBounds dipindah ke luar blok geojson: pakai bounds poligon
                    // bila ada, else bounds titik bangunan, else biarkan viewport.
                    if (layerPoligon) {
                        map.fitBounds(layerPoligon.getBounds(), { padding: [30, 30] });
                    } else if (layerMarker.getLayers().length) {
                        map.fitBounds(layerMarker.getBounds(), { padding: [30, 30] });
                    }

                    const info = res.info || null;
                    isiPanel(
                        { info: info, jumlah_bangunan: res.jumlah_bangunan },
                        res.pesan,
                        null,
                        null,
                        'Dropdown'
                    );

                    infoTerakhir = info;
                    muatLevelAktif(info);
                })
                .fail(function(jqxhr) {
                    const pesan = (jqxhr.responseJSON && jqxhr.responseJSON.pesan)
                        ? jqxhr.responseJSON.pesan
                        : 'Gagal memuat data peta. Coba lagi.';
                    isiPanel(null, pesan, null, null, 'Dropdown');
                });
        });

        // 8. Tombol layer: Sub SLS / SLS / Desa / Kecamatan
        $('.btn-layer').on('click', function() {
            const btn = $(this);
            const level = btn.data('level');
            const aktif = btn.attr('aria-pressed') !== 'true';

            btn.attr('aria-pressed', aktif ? 'true' : 'false');
            levelAktif[level] = aktif;

            if (aktif) {
                LAYER[level].addTo(map);
                muatLevelAktif(infoTerakhir);
            } else {
                map.removeLayer(LAYER[level]);
                LAYER[level].clearLayers();
            }
        });

        // 9. Reset Peta
        $('#btnResetPeta').on('click', function() {
            kosongkanPeta();
            $('#panel-hasil').addClass('d-none');
            setPesan(null);
            map.setView([-0.9471, 100.4172], 9);
        });

        // 10. Fitur "Lokasi Saya" (Geolocation API)
        $('#btnLokasiSaya').click(function() {
            const btn = $(this);

            if (!navigator.geolocation) {
                alert('Fitur Geolocation tidak didukung oleh browser Anda.');
                return;
            }

            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Mencari Lokasi...
            `);

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    // Hapus marker lokasi lama jika ada
                    layerPengguna.clearLayers();

                    // Tambahkan marker lingkaran lokasi baru
                    const circle = L.circle([lat, lng], {
                        radius: accuracy,
                        color: '#206bc4',
                        fillColor: '#206bc4',
                        fillOpacity: 0.15
                    }).addTo(layerPengguna);

                    const marker = L.circleMarker([lat, lng], {
                        radius: 8,
                        color: '#ffffff',
                        weight: 2,
                        fillColor: '#d63939',
                        fillOpacity: 1
                    }).bindPopup(`
                        <div class="p-1 text-center">
                            <strong class="text-danger">Lokasi Anda Saat Ini</strong><br>
                            <span class="small text-muted">Akurasi: ~${Math.round(accuracy)} meter</span>
                        </div>
                    `).addTo(layerPengguna);

                    // Pindahkan tampilan peta ke lokasi pengguna
                    map.setView([lat, lng], 17);
                    marker.openPopup();

                    // Kembalikan status tombol
                    btn.prop('disabled', false).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-current-location" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M12 12m-8 0a8 8 0 1 0 16 0a8 8 0 1 0 -16 0" />
                            <path d="M12 2l0 2" />
                            <path d="M12 20l0 2" />
                            <path d="M20 12l2 0" />
                            <path d="M2 12l2 0" />
                        </svg>
                        Lokasi Saya
                    `);

                    // Identifikasi batas wilayah lokasi pengguna
                    identifikasiTitik(lat, lng, 'Lokasi Saya');
                },
                function(error) {
                    btn.prop('disabled', false).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-current-location" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M12 12m-8 0a8 8 0 1 0 16 0a8 8 0 1 0 -16 0" />
                            <path d="M12 2l0 2" />
                            <path d="M12 20l0 2" />
                            <path d="M20 12l2 0" />
                            <path d="M2 12l2 0" />
                        </svg>
                        Lokasi Saya
                    `);

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Izin akses lokasi ditolak oleh browser. Mohon izinkan akses lokasi di browser Anda.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Informasi lokasi tidak tersedia.");
                            break;
                        case error.TIMEOUT:
                            alert("Waktu permintaan lokasi habis.");
                            break;
                        default:
                            alert("Terjadi kesalahan saat mengambil lokasi.");
                            break;
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

    });
</script>
<?= $this->endSection(); ?>
