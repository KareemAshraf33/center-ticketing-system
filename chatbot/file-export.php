<?php

require '../vendor/autoload.php';
require 'dbcon.php';

function getDataById($id) 
{
    global $conn;

    $sql = "SELECT * FROM schedule WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return null;
    }
}

function exportToExcel($data, $studentId, $tempDirectory) {
    $objPHPExcel = new PHPExcel();

    // Create a new worksheet
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Add headers to the worksheet
    $headers = array_keys($data[0]);
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Add data rows to the worksheet
    $row = 2;
    foreach ($data as $item) {
        $col = 'A';
        foreach ($item as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }

    // Save the Excel file in the public directory
    $filename = "student_" . $studentId . ".xlsx";
    $tempFilePath = public_path($tempDirectory . $filename);

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save($tempFilePath);

    return $tempFilePath;
}

?>
