<?php


require_once '../../vendor/autoload.php';
require_once 'Connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$stmt = $conn->query("SELECT users.*,userlevel.userLevel FROM users inner join userlevel on users.ID = userlevel.userId");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// dodajemo podatke za prvi red
$column = 'A';
foreach ($data[0] as $header => $value) 
{
    $sheet->setCellValue($column . '1', $header);
    $column++;
}

// zatim za ostale
$row = 2;
foreach ($data as $rowData)
 {
    $column = 'A';
    foreach ($rowData as $value) 
    {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }
    $row++;
}


$writer = new Xlsx($spreadsheet);

// ime fajla
$filename = 'data_export.xlsx';

// downloadujemo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// saveujemo spreadsheet na output
$writer->save('php://output');