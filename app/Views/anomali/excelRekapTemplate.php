<?php
// Bagian Controller / File Download Template Anda
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Template Konfirmasi');

// 1. Atur Header Tabel
$headers = [
    'ID (Jangan Diubah)',
    'Kode Wilayah',
    'Nama KRT',
    'Nama ART',
    'Kode Anomali',
    'Apakah Kondisi Lapangan (1. Ya, 2. Tidak)',
    'Isi Konfirmasi / Tanggapan Lapangan'
];

$sheet->fromArray($headers, NULL, 'A1');

// 2. Styling Header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0D6EFD'], // Warna Biru Bootstrap
    ],
];

// Warnai kolom isian kuning untuk kolom 5 dan 6 (Index F & G)
$inputHeaderStyle = $headerStyle;
$inputHeaderStyle['font']['color'] = ['rgb' => '000000'];
$inputHeaderStyle['fill']['startColor'] = ['rgb' => 'FFDA6A']; // Warna Kuning

$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
$sheet->getStyle('F1:G1')->applyFromArray($inputHeaderStyle);
$sheet->getRowDimension('1')->setRowHeight(30);

// 3. Isi Data Data dari Database ($listAnom)
$rowNum = 2;
foreach ($listAnom as $row) {
    $sheet->setCellValue('A' . $rowNum, $row['id_anomali']);
    $sheet->setCellValue('B' . $rowNum, $row['id_wilayah']);
    $sheet->setCellValue('C' . $rowNum, $row['nm_krt'] ?? $row['nm_nrt'] ?? '-');
    $sheet->setCellValue('D' . $rowNum, $row['nm_art'] ?? '-');
    $sheet->setCellValue('E' . $rowNum, $row['kode_anomali']);
    $sheet->setCellValue('F' . $rowNum, ''); // Sengaja dikosongkan untuk diisi petugas
    $sheet->setCellValue('G' . $rowNum, !empty($row['konfirmasi']) ? $row['konfirmasi'] : '');

    // Set Format ID dan Kode Wilayah sebagai TEXT agar nol di awal tidak hilang (@)
    $sheet->getStyle('A' . $rowNum)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
    $sheet->getStyle('B' . $rowNum)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    // Rata Tengah untuk kolom ID, Wilayah, Kode Anomali, dan IsLap
    $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $rowNum . ':F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $rowNum++;
}

// Jika data kosong
if (empty($listAnom)) {
    $sheet->setCellValue('A2', 'Data Template kosong.');
    $sheet->mergeCells('A2:G2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// 4. Auto-size Lebar Kolom
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 5. Berikan border tipis ke seluruh cell data
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC'],
        ],
    ],
];
$sheet->getStyle('A1:G' . ($rowNum - 1))->applyFromArray($styleArray);

// 6. Proses Download (.xlsx asli)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Template_Konfirmasi_Anomali_' . date('Ymd') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
