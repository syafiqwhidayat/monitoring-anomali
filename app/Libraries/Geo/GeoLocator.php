<?php

namespace App\Libraries\Geo;

/**
 * Utilitas geometri untuk fitur Peta (Sub SLS / SLS / Desa / Kecamatan).
 *
 * Format data yang dipakai:
 * - `wilayah_geojson.geojson` berisi FeatureCollection dengan 1 feature,
 *   geometry MultiPolygon (kadang Polygon), koordinat WGS84 [lng, lat, 0].
 *
 * CATATAN PENTING:
 * `idsubsls` (contoh "1306010001000101") adalah string angka. PHP Promote
 * string angka yang masih muat di int menjadi integer saat dijadikan key array,
 * sehingga perbandingan `===` terhadap string bisa gagal diam-diam.
 * Karena itu semua koleksi poligon di kelas ini disimpan sebagai LIST bernomor
 * dengan field 'id' bertipe string - JANGAN pernah memakai id sebagai key array.
 */
class GeoLocator
{
    /**
     * Ray casting (even-odd rule) untuk satu poligon.
     *
     * @param array $rings List ring milik satu poligon: ring[0] = batas luar,
     *                      ring berikutnya = lubang (hole). Hole ikut dihitung
     *                      sehingga titik di dalam lubang terbaca "di luar".
     */
    public static function pointInPolygon(float $lng, float $lat, array $rings): bool
    {
        $inside = false;

        foreach ($rings as $ring) {
            if (!is_array($ring) || count($ring) < 3) {
                continue;
            }

            $n = count($ring);
            $j = $n - 1;

            for ($i = 0; $i < $n; $i++) {
                $xi = $ring[$i][0];
                $yi = $ring[$i][1];
                $xj = $ring[$j][0];
                $yj = $ring[$j][1];

                // Titik yang tepat di atas rusuk dianggap berada di dalam.
                if (self::pointOnSegment($lng, $lat, $xi, $yi, $xj, $yj)) {
                    return true;
                }

                // Short-circuit &: bagian kedua hanya dievaluasi bila titik
                // melewati rentang lat, sehingga tidak ada pembagian dengan nol.
                if ((($yi > $lat) !== ($yj > $lat))
                    && ($lng < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi)) {
                    $inside = !$inside;
                }

                $j = $i;
            }
        }

        return $inside;
    }

    /**
     * Ambil list poligon dari GeoJSON hasil decode.
     * Menerima FeatureCollection, Feature, atau geometry langsung.
     *
     * @return array List poligon; tiap poligon = list ring.
     */
    public static function extractPolygons(array $geo): array
    {
        $type = $geo['type'] ?? '';

        if ($type === 'Polygon' || $type === 'MultiPolygon') {
            return self::polygonsOfGeometry($geo);
        }

        $polygons = [];

        foreach (($geo['features'] ?? []) as $feature) {
            $geometry = $feature['geometry'] ?? null;
            if (!is_array($geometry)) {
                continue;
            }

            foreach (self::polygonsOfGeometry($geometry) as $polygon) {
                $polygons[] = $polygon;
            }
        }

        return $polygons;
    }

    /**
     * Pecah satu geometry menjadi list poligon.
     * Polygon  -> 1 poligon; MultiPolygon -> N poligon.
     * Tiap poligon = list ring (ring[0] batas luar, berikutnya lubang).
     */
    protected static function polygonsOfGeometry(array $geometry): array
    {
        $type = $geometry['type'] ?? '';
        $coordinates = $geometry['coordinates'] ?? [];

        if ($type === 'Polygon') {
            $polygon = self::normalizeRings($coordinates);
            return $polygon === [] ? [] : [$polygon];
        }

        if ($type === 'MultiPolygon') {
            $polygons = [];
            foreach ($coordinates as $rings) {
                if (!is_array($rings)) {
                    continue;
                }
                $polygon = self::normalizeRings($rings);
                if ($polygon !== []) {
                    $polygons[] = $polygon;
                }
            }
            return $polygons;
        }

        return [];
    }

    /**
     * Rapikan ring menjadi list titik [lng, lat]; buang ring dengan < 3 titik.
     */
    protected static function normalizeRings(array $rings): array
    {
        $result = [];

        foreach ($rings as $ring) {
            if (!is_array($ring)) {
                continue;
            }

            $points = [];

            foreach ($ring as $point) {
                if (!is_array($point) || !isset($point[0], $point[1])) {
                    continue;
                }
                $points[] = [(float)$point[0], (float)$point[1]];
            }

            if (count($points) >= 3) {
                $result[] = $points;
            }
        }

        return $result;
    }

    /**
     * Tahap 1: bangun index bbox + luas untuk setiap baris wilayah_geojson.
     * GeoJSON di-decode satu per satu lalu dibuang lagi, sehingga memori tetap
     * rendah (terukur: 1.940 baris hanya ~32 MB peak).
     *
     * @param array $geojsonRows List baris ['idsubsls' => string, 'geojson' => string]
     *
     * @return array List entry ['id','pos','bbox','area']
     */
    public static function buildIndex(array $geojsonRows): array
    {
        $geojsonRows = array_values($geojsonRows);
        $index = [];

        foreach ($geojsonRows as $pos => $row) {
            $id = (string)($row['idsubsls'] ?? '');
            if ($id === '') {
                continue;
            }

            $geo = json_decode((string)($row['geojson'] ?? ''), true);
            if (!is_array($geo)) {
                continue;
            }

            $polygons = self::extractPolygons($geo);
            if ($polygons === []) {
                continue;
            }

            $minX = INF;
            $minY = INF;
            $maxX = -INF;
            $maxY = -INF;
            $area = 0.0;

            foreach ($polygons as $rings) {
                if ($rings === []) {
                    continue;
                }

                foreach ($rings[0] as $point) {
                    if ($point[0] < $minX) { $minX = $point[0]; }
                    if ($point[0] > $maxX) { $maxX = $point[0]; }
                    if ($point[1] < $minY) { $minY = $point[1]; }
                    if ($point[1] > $maxY) { $maxY = $point[1]; }
                }

                $area += abs(self::ringArea($rings[0]));
            }

            if ($minX === INF) {
                continue;
            }

            $index[] = [
                'id'   => $id,
                'pos'  => $pos,
                'bbox' => [$minX, $minY, $maxX, $maxY],
                'area' => $area,
            ];
        }

        return $index;
    }

    /**
     * Tahap 2: dari index, saring kandidat lewat bbox lalu uji containment.
     * Kandidat yang memuat titik dipilih yang luasnya paling kecil (paling spesifik).
     *
     * @return array ['found','idsubsls','luas','geojson','jumlah_kandidat','jumlah_bbox']
     */
    public static function findContaining(array $index, array $geojsonRows, float $lng, float $lat): array
    {
        $geojsonRows = array_values($geojsonRows);

        $hasil = [
            'found'           => false,
            'idsubsls'        => null,
            'luas'            => null,
            'geojson'         => null,
            'jumlah_kandidat' => 0,
            'jumlah_bbox'     => 0,
        ];

        $kandidat = [];

        foreach ($index as $entry) {
            $bbox = $entry['bbox'];

            if ($lng < $bbox[0] || $lng > $bbox[2] || $lat < $bbox[1] || $lat > $bbox[3]) {
                continue;
            }

            $kandidat[] = $entry;
        }

        $hasil['jumlah_bbox'] = count($kandidat);

        foreach ($kandidat as $entry) {
            $row = $geojsonRows[$entry['pos']] ?? null;
            if ($row === null) {
                continue;
            }

            $geo = json_decode((string)($row['geojson'] ?? ''), true);
            if (!is_array($geo)) {
                continue;
            }

            foreach (self::extractPolygons($geo) as $rings) {
                if (!self::pointInPolygon($lng, $lat, $rings)) {
                    continue;
                }

                $hasil['jumlah_kandidat']++;

                $luas = abs(self::ringArea($rings[0]));

                if ($hasil['found'] === false || $luas < $hasil['luas']) {
                    $hasil['found']    = true;
                    $hasil['idsubsls'] = $entry['id'];
                    $hasil['luas']     = $luas;
                    $hasil['geojson']  = $geo;
                }

                // Satu baris idsubsls = satu poligon; lanjut ke kandidat berikutnya.
                break;
            }
        }

        return $hasil;
    }

    /**
     * Gabungkan outline dari beberapa poligon anak menjadi satu ring tertutup.
     *
     * Hanya berlaku untuk data yang berupa tessellasi (poligon anak berbagi sisi).
     * Bila hasilnya tidak valid (rantai tidak tertutup, atau luas union tidak
     * conserving) method mengembalikan null dan pemanggil memakai fallback group.
     *
     * @param array $geojsonRows List baris ['idsubsls' => string, 'geojson' => string]
     *
     * @return array|null List ring (koordinat [lng, lat]) atau null bila tidak valid.
     */
    public static function unionOutline(array $geojsonRows): ?array
    {
        $geojsonRows = array_values($geojsonRows);

        $edges = [];
        $coords = [];
        $totalArea = 0.0;

        foreach ($geojsonRows as $row) {
            $geo = json_decode((string)($row['geojson'] ?? ''), true);
            if (!is_array($geo)) {
                return null;
            }

            foreach (self::extractPolygons($geo) as $rings) {
                // Poligon berlubang: union belum ditangani, pakai fallback.
                if (count($rings) !== 1) {
                    return null;
                }

                $ring = $rings[0];
                $totalArea += abs(self::ringArea($ring));

                $n = count($ring);
                for ($i = 0; $i < $n; $i++) {
                    $p1 = $ring[$i];
                    $p2 = $ring[($i + 1) % $n];

                    $ka = self::pointKey($p1[0], $p1[1]);
                    $kb = self::pointKey($p2[0], $p2[1]);

                    if ($ka === $kb) {
                        continue;
                    }

                    $coords[$ka] = [$p1[0], $p1[1]];
                    $coords[$kb] = [$p2[0], $p2[1]];

                    $edgeKey = self::edgeKey($ka, $kb);
                    if (!isset($edges[$edgeKey])) {
                        $edges[$edgeKey] = ['a' => $ka, 'b' => $kb, 'count' => 0];
                    }
                    $edges[$edgeKey]['count']++;
                }
            }
        }

        if ($edges === [] || $totalArea <= 0) {
            return null;
        }

        // Edge dengan count 1 = batas luar. Edge count 2 = sisi dalam (dibuang).
        $adjacency = [];

        foreach ($edges as $edge) {
            if ($edge['count'] !== 1) {
                continue;
            }

            $adjacency[$edge['a']][] = $edge['b'];
            $adjacency[$edge['b']][] = $edge['a'];
        }

        if ($adjacency === []) {
            return null;
        }

        $used = [];
        $result = [];
        $openChains = 0;

        foreach ($adjacency as $node => $neighbours) {
            foreach ($neighbours as $neighbour) {
                $edgeKey = self::edgeKey($node, $neighbour);

                if (isset($used[$edgeKey])) {
                    continue;
                }
                $used[$edgeKey] = true;

                $chain = [$node];
                $current = $neighbour;
                $closed = false;

                while (true) {
                    $chain[] = $current;

                    if ($current === $chain[0]) {
                        $closed = true;
                        break;
                    }

                    $next = null;

                    foreach ($adjacency[$current] ?? [] as $candidate) {
                        $candidateKey = self::edgeKey($current, $candidate);

                        if (isset($used[$candidateKey])) {
                            continue;
                        }

                        $used[$candidateKey] = true;
                        $next = $candidate;
                        break;
                    }

                    if ($next === null) {
                        break;
                    }

                    $current = $next;
                }

                if (!$closed) {
                    $openChains++;
                    continue;
                }

                // Buang titik penutup yang sama dengan titik awal.
                if (count($chain) > 1 && $chain[0] === $chain[count($chain) - 1]) {
                    array_pop($chain);
                }

                $points = [];
                foreach ($chain as $key) {
                    if (isset($coords[$key])) {
                        $points[] = $coords[$key];
                    }
                }

                if (count($points) >= 3) {
                    $result[] = $points;
                }
            }
        }

        if ($openChains > 0 || $result === []) {
            return null;
        }

        // Validasi konservasi luas: union harus ~= jumlah luas poligon anak.
        $unionArea = 0.0;
        foreach ($result as $ring) {
            $unionArea += abs(self::ringArea($ring));
        }

        $ratio = $unionArea / $totalArea;

        if ($ratio < 0.95 || $ratio > 1.05) {
            return null;
        }

        return $result;
    }

    /**
     * Luas ring (shoelace). Negatif bila orientasi berlawanan arah jam.
     */
    public static function ringArea(array $ring): float
    {
        $area = 0.0;
        $n = count($ring);

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $area += ($ring[$j][0] * $ring[$i][1]) - ($ring[$i][0] * $ring[$j][1]);
        }

        return $area / 2;
    }

    /**
     * Kunci titik yang stabil: 7 desimal, tanpa tanda minus nol.
     */
    protected static function pointKey(float $x, float $y): string
    {
        if ($x == 0.0) { $x = 0.0; }
        if ($y == 0.0) { $y = 0.0; }

        return sprintf('%.7F', $x) . ',' . sprintf('%.7F', $y);
    }

    /**
     * Kunci edge tak berarah (urutan dua ujung tidak berpengaruh).
     */
    protected static function edgeKey(string $a, string $b): string
    {
        return ($a < $b) ? $a . '~' . $b : $b . '~' . $a;
    }

    /**
     * Apakah titik berada tepat di atas segment garis.
     */
    protected static function pointOnSegment(float $x, float $y, float $x1, float $y1, float $x2, float $y2): bool
    {
        $cross = (($x - $x1) * ($y2 - $y1)) - (($y - $y1) * ($x2 - $x1));
        $eps = 1e-11;

        if (abs($cross) > $eps) {
            return false;
        }

        return $x >= min($x1, $x2) - $eps && $x <= max($x1, $x2) + $eps
            && $y >= min($y1, $y2) - $eps && $y <= max($y1, $y2) + $eps;
    }
}
