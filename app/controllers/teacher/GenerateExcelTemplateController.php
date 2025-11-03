<?php

namespace App\Controllers\Teacher;

use App\Controllers\Services\Redirector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SlimSession\Helper as Session;



class GenerateExcelTemplateController
{
    public Spreadsheet $spreadsheet;
    public Session $session;
    public function __construct(Spreadsheet $spreadsheet, Session $session)
    {
        $this->spreadsheet = $spreadsheet;
        $this->session = $session;
    }

    public function __invoke()
    {
        $userid = $this->session->get('userid');
        if (!$userid) {
            Redirector::redirect_to('/login');
        }
        $sheet = $this->spreadsheet->getActiveSheet();

        // Define column headers
        $headers = ['#', 'Student ID', 'Student Fullname', 'Course Name'];

        // Write headers to the first row
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Make headers bold and center aligned
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Adjust column widths for better spacing
        $sheet->getColumnDimension('A')->setWidth(8);   // #
        $sheet->getColumnDimension('B')->setWidth(20);  // Student ID
        $sheet->getColumnDimension('C')->setWidth(30);  // Student Fullname
        $sheet->getColumnDimension('D')->setWidth(25);  // Course Name

        // Example: Add blank sample rows (optional)
        $sheet->fromArray([
            ['', '', '', ''],
            ['', '', '', '']
        ], null, 'A2');

        // Save Excel file to a path
        $filename = storage_path('exceltemplate/student_template.xlsx');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filename);

        // Output directly to browser for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="student_template.xlsx"');
        $writer->save('php://output');
        exit;

        // This won't execute after exit, but keep it for future redirect logic
        Redirector::redirectToLastUrl('teacher.student.index');
    }
}
