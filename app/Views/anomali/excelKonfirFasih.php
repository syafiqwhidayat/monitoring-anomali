<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Konfirmasi Fasih');

// 1. Set Judul Header Laporan
$sheet->mergeCells('A1:J1');
$sheet->setCellValue('A1', 'DAFTAR CATATAN EVALUASI ANOMALI PETUGAS (KOREKSI ULANG)');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// 2. Set Header Kolom Tabel
$headers = [
    'A3' => 'No',
    'B3' => 'Kode Wilayah',
    'C3' => 'Kode Anomali',
    'D3' => 'Kriteria Kesalahan Data',
    'E3' => 'Kode Assignment',
    'F3' => 'Nama Objek / Ruta',
    'G3' => 'Isi Jawaban Petugas',
    'H3' => 'Tanggal Konfirmasi',
    'I3' => 'Petugas PPL',
    'J3' => 'Petugas PML'
];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// Styling Header Tabel (Warna Oranye BPS & Bold)
$sheet->getStyle('A3:J3')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
$sheet->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EE8911');
$sheet->getStyle('A3:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getRowDimension(3)->setRowHeight(25);

// 3. Pengisian Data
$rowNum = 4;
$no = 1;

if (!empty($listAnom)) {
    foreach ($listAnom as $row) {
        $sheet->setCellValue("A{$rowNum}", $no++);
        // Menggunakan Explicit String agar angka/nol depan pada kode wilayah & assignment tidak hilang
        $sheet->setCellValueExplicit("B{$rowNum}", $row['id_wilayah'] ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue("C{$rowNum}", $row['kode_anomali'] ?? '');
        $sheet->setCellValue("D{$rowNum}", $row['detil_anomali'] ?? '');
        $sheet->setCellValueExplicit("E{$rowNum}", $row['kd_assigment'] ?? '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue("F{$rowNum}", $row['nm_krt'] ?? $row['nm_art'] ?? $row['nm_nrt'] ?? '-');
        $sheet->setCellValue("G{$rowNum}", $row['konfirmasi'] ?? '');
        $sheet->setCellValue("H{$rowNum}", !empty($row['date_konfirmasi']) ? date('Y-m-d H:i', strtotime($row['date_konfirmasi'])) : '-');
        $sheet->setCellValue("I{$rowNum}", $row['nm_ppl'] ?? '-');
        $sheet->setCellValue("J{$rowNum}", $row['nm_pml'] ?? '-');

        // Center alignment untuk kolom No, Wilayah, Kode, Tanggal
        $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Memberi warna latar hijau lembut untuk kolom jawaban petugas
        $sheet->getStyle("G{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2EFDA');

        $rowNum++;
    }
}

// 4. Set Border Seluruh Tabel
$lastRow = $rowNum - 1;
if ($lastRow >= 3) {
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'CCCCCC'],
            ],
        ],
    ];
    $sheet->getStyle("A3:J{$lastRow}")->applyFromArray($styleArray);
}

// 5. Auto-width untuk Setiap Kolom
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 6. Header HTTP Response untuk Download File .xlsx
$wil = !empty($filterWilayah) ? $filterWilayah : 'Semua';
$fileName = "Konfirmasi_Fasih_" . $wil . "_" . date('Ymd_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');
header('Pragma: no-cache');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
