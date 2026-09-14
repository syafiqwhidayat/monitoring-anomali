<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rekap Assignment');

// 1. Judul Utama
$sheet->mergeCells('A1:L1');
$sheet->setCellValue('A1', 'REKAPITULASI JAWABAN ANOMALI BERDASARKAN OBJEK (ASSIGNMENT)');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E9ECEF');
$sheet->getRowDimension(1)->setRowHeight(35);

// 2. Header Tabel
$headers = [
    'A3' => 'Kode Wilayah',
    'B3' => 'Id_Assigment',
    'C3' => 'Id_Roster',
    'D3' => 'Nama KRT',
    'E3' => 'Nama Roster',
    'F3' => 'Nama PPL',
    'G3' => 'Nama PML',
    'H3' => 'Kode Anomali',
    'I3' => 'Detil Anomali',
    'J3' => 'Konfirmasi',
    'K3' => 'Kondisi Lapangan',
    'L3' => 'Status Anomali',
    'M3' => 'Waktu Update'
];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// Styling Header (Bulk)
$sheet->getStyle('A3:M3')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
$sheet->getStyle('A3:M3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0D6EFD');
$sheet->getStyle('I3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDA6A');
$sheet->getStyle('I3')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
$sheet->getStyle('A3:M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getRowDimension(3)->setRowHeight(28);

// 3. Pengisian Data
$startRow = 4;
$currentRow = $startRow;

if (!empty($listAnom)) {
    $rowspans = array_count_values(array_column($listAnom, 'id_assignment_obj'));
    $displayedAssignments = [];

    foreach ($listAnom as $row) {
        $currentId = $row['id_assignment_obj'];
        $isFirstRow = !in_array($currentId, $displayedAssignments);

        if ($isFirstRow) {
            $displayedAssignments[] = $currentId;
            $span = $rowspans[$currentId];
            $endMergeRow = $currentRow + $span - 1;

            $sheet->setCellValueExplicit("A{$currentRow}", $row['id_wilayah'] ?? '', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("B{$currentRow}", $row['kd_krt'] ?? '', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$currentRow}", $row['kd_art'] ?? '', DataType::TYPE_STRING);
            $sheet->setCellValue("D{$currentRow}", $row['nm_krt'] ?? $row['nm_nrt'] ?? '-');
            $sheet->setCellValue("E{$currentRow}", $row['nm_art'] ?? '-');
            $sheet->setCellValue("F{$currentRow}", $row['nama_ppl'] ?? '-');
            $sheet->setCellValue("G{$currentRow}", $row['nama_pml'] ?? '-');

            if ($span > 1) {
                foreach (range('A', 'G') as $col) {
                    $sheet->mergeCells("{$col}{$currentRow}:{$col}{$endMergeRow}");
                }
            }
        }

        $sheet->setCellValue("H{$currentRow}", $row['kode_anomali'] ?? '');
        $sheet->setCellValue("I{$currentRow}", $row['detil_anomali'] ?? '');
        $sheet->setCellValue("J{$currentRow}", !empty($row['konfirmasi']) ? $row['konfirmasi'] : '[ Belum Ada Konfirmasi ]');
        $sheet->setCellValue("K{$currentRow}", ((int)$row['is_lap'] === 1) ? 'Kondisi Lapangan' : 'Perbaikan Fasih');
        $sheet->setCellValue("L{$currentRow}", ((int)$row['is_insert'] === 1) ? 'Anomali Aktif' : 'Anomali Clean');
        $sheet->setCellValue("M{$currentRow}", !empty($row['date_updated']) ? date('Y-m-d H:i', strtotime($row['date_konfirmasi'])) : '-');

        $currentRow++;
    }
}

// 4. Styling Bulk / Massal (Jauh Lebih Cepat daripada Styling Per Baris)
$lastDataRow = $currentRow - 1;

if ($lastDataRow >= 4) {
    // Vertikal top untuk seluruh data
    $sheet->getStyle("A4:L{$lastDataRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

    // Horizontal alignment masal
    $sheet->getStyle("A4:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("F4:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("J4:L{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Bold untuk Kode Anomali
    $sheet->getStyle("G4:G{$lastDataRow}")->getFont()->setBold(true);

    // Border untuk seluruh tabel
    $sheet->getStyle("A3:L{$lastDataRow}")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'CCCCCC'],
            ],
        ],
    ]);
}

// 5. Lebar Kolom Statis (Ganti AutoSize yang memakan CPU)
$columnWidths = [
    'A' => 25,
    'B' => 20,
    'C' => 20,
    'D' => 18,
    'E' => 18,
    'F' => 16,
    'G' => 14,
    'H' => 40,
    'I' => 40,
    'J' => 16,
    'K' => 16,
    'L' => 18,
    'M' => 18
];
foreach ($columnWidths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// 6. Response Header untuk Download
$fileName = "Rekap_Anomali_Per_Assignment_" . date('Ymd_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');
header('Pragma: no-cache');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
