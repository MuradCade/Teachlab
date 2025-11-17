<?php

namespace App\Controllers\Teacher;

use App\Controllers\Services\Redirector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SlimSession\Helper as Session;
use App\Models\Attandence;

class Exportall_attendancedata
{
    public Spreadsheet $spreadsheet;
    public Session $session;

    public function __construct(Spreadsheet $spreadsheet, Session $session)
    {
        $this->spreadsheet = $spreadsheet;
        $this->session = $session;
    }

    public function __invoke($request, $response, $args)
    {
        $userid = $this->session->get('userid');
        if (!$userid) {
            Redirector::redirect_to('/login');
        }

        // Fetch attendance data
        $coursename = $args['courseid']; // or pass via route
        $attendancemodel = Attandence::where('courseid', $coursename)
            ->where('userid', $userid)
            ->with(['student', 'course'])
            ->select('studentid', 'courseid')
            ->selectRaw('SUM(attandence_marks) as totalAttendancemarks')
            ->groupBy('studentid', 'courseid')
            ->get();

        $sheet = $this->spreadsheet->getActiveSheet();

        // Headers
        $headers = ['#', 'Student ID', 'Student Fullname', 'Course Name', 'Total Attendance Marks'];

        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style headers
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Write attendance rows
        $row = 2;
        foreach ($attendancemodel as $index => $attendance) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $attendance->studentid);
            $sheet->setCellValue("C{$row}", $attendance->student->studentname);
            $sheet->setCellValue("D{$row}", $attendance->course->coursename);
            $sheet->setCellValue("E{$row}", $attendance->totalAttendancemarks);

            $row++;
        }

        // Output Excel file to browser
        $filename = "all_student_attendance_report.xlsx";

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: public");

        $writer = new Xlsx($this->spreadsheet);
        $writer->save("php://output");
        exit;
    }
}
