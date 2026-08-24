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
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                            <path d="M7 9l5 -5l5 5" />
                            <path d="M12 4l0 12" />
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

        let layerPolygonGroup = L.layerGroup().addTo(map);
        let layerMarkerGroup = L.layerGroup().addTo(map);
        let userLocationMarker = null;

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
        $.getJSON('<?= base_url("peta/get-kabupaten"); ?>')
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

                $.getJSON('<?= base_url("peta/get-kecamatan"); ?>', {
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

                $.getJSON('<?= base_url("peta/get-desa"); ?>', {
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

                $.getJSON('<?= base_url("peta/get-sls"); ?>', {
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

                $.getJSON('<?= base_url("peta/get-subsls"); ?>', {
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
                            const kdSubSls = item.kd_subsls || item.nm_subsls || idSubSls;
                            return {
                                value: String(idSubSls).trim(),
                                text: `[${kdSubSls}] ${item.nm_subsls || kdSubSls}`
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

        // 5. Render Map Data
        $('#btnTampilkanPeta').click(function() {
            const idSubSls = $('#sel-subsls').val();
            if (!idSubSls) return;

            layerPolygonGroup.clearLayers();
            layerMarkerGroup.clearLayers();

            $.getJSON('<?= base_url("peta/get-data-map"); ?>/' + idSubSls, function(res) {
                if (res.status === 'success') {
                    if (res.geojson) {
                        const geoJsonLayer = L.geoJSON(res.geojson, {
                            style: {
                                color: '#0054a6',
                                weight: 3,
                                opacity: 0.9,
                                fillColor: '#206bc4',
                                fillOpacity: 0.2
                            }
                        }).addTo(layerPolygonGroup);

                        map.fitBounds(geoJsonLayer.getBounds(), {
                            padding: [30, 30]
                        });
                    }

                    if (res.bangunan && res.bangunan.length > 0) {
                        res.bangunan.forEach(b => {
                            const jnsBangunan = b.jns_bangunan_value ? b.jns_bangunan_value : '-';
                            const marker = L.marker([b.latitude, b.longitude])
                                .bindPopup(`
                                    <div class="p-1">
                                        <div class="fw-bold mb-1 text-primary">No. Bangunan: ${b.no_bangunan}</div>
                                        <div class="small mb-1"><strong>Principal:</strong> ${b.nama_principal}</div>
                                        <div class="small mb-1"><strong>Jenis Bangunan:</strong> ${jnsBangunan}</div>
                                        <div class="small text-muted"><strong>ID SubSLS:</strong> ${b.idsubsls}</div>
                                    </div>
                                `);
                            layerMarkerGroup.addLayer(marker);
                        });
                    }
                }
            });
        });

        // 6. Fitur "Lokasi Saya" (Geolocation API)
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
                    if (userLocationMarker) {
                        map.removeLayer(userLocationMarker);
                    }

                    // Tambahkan marker lingkaran lokasi baru
                    userLocationMarker = L.layerGroup().addTo(map);

                    const circle = L.circle([lat, lng], {
                        radius: accuracy,
                        color: '#206bc4',
                        fillColor: '#206bc4',
                        fillOpacity: 0.15
                    }).addTo(userLocationMarker);

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
                    `).addTo(userLocationMarker);

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