<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelSeparationController extends Controller
{

    public function showForm()
    {
        return view('upload-excel');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file);
    
        $studentData = [];
    
        foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
    
            $studentId = $rowData[5]; // Assuming the student ID is in the 6th column
    
            // Initialize an array for each student ID if it doesn't exist
            if (!isset($studentData[$studentId])) {
                $studentData[$studentId] = [];
            }
    
            // Store the entire row's data for the student ID
            $studentData[$studentId][] = $rowData;
        }
    
        foreach ($studentData as $studentId => $studentRows) {
            $studentSheet = new Spreadsheet();
            $sheet = $studentSheet->getActiveSheet();
    
            // Add headers to the sheet
            $headers = [
                'training_class', 'training_place', 'level', 'department', 'program', 'training_id',
                'student_name', 'day', 'time', 'subject', 'subject_name', 'subject_status', 'lastupdate',
                'reference_num', 'from_time', 'to_time', 'credit_hours_count', 'credits', 'lecture',
                'room', 'room_name', 'assistant_name', 'credit_hours'
            ];
    
            foreach ($headers as $colIndex => $header) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, 1, $header);
            }
    
            // Add data rows for the student
            $rowIndex = 2;
            foreach ($studentRows as $rowData) {
                foreach ($rowData as $colIndex => $cellValue) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex, $cellValue);
                }
                $rowIndex++;
            }
    
            $writer = new Xlsx($studentSheet);
            $fileName = "student_{$studentId}_schedule.xlsx";
            $filePath = public_path("student_files/{$fileName}");
    
            try {
                $writer->save($filePath);
            } catch (\Exception $e) {
                // Log the exception message
                return "Error saving Excel file: " . $e->getMessage();
            }
        }
    
        // return "Excel files saved!";
        return redirect('/tickets/list'); 
    }

}



