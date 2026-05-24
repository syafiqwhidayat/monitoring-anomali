<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

Events::on('login', function ($user) {
    $kegiatanModel = new \App\Models\KegiatanModel();
    $daftarKegiatan = $kegiatanModel->getKegiatanDesc();

    // 1. Set default session agar BaseController tidak error
    session()->set('is_rt', 0);
    session()->set('isOrganik', false);

    // 2. Cek Identitas (BPS atau bukan) dengan lebih aman
    $identities = auth()->user()->getIdentities();
    if (!empty($identities)) {
        $isOrganik = str_ends_with($identities[0]->secret, "@bps.go.id");
        session()->set('isOrganik', $isOrganik);
    }

    // 3. Set Role
    $groups = auth()->user()->getGroups();
    if (!empty($groups)) {
        session()->set('aktif_role', $groups[0]);
    }


    // 4. Set Data Kegiatan jika ada
    if (!empty($daftarKegiatan)) {
        $terbaru = $daftarKegiatan[0];
        session()->set('aktif_kegiatan', $terbaru['id']);
        session()->set('nama_kegiatan', $terbaru['nama']);

        // Gunakan Null Coalescing di sini juga untuk berjaga-jaga
        session()->set('is_rt', $terbaru['is_rt'] ?? 0);
        session()->set('level_wilayah', $terbaru['level_wilayah'] ?? null);
    }
});
