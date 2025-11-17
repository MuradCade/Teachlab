<?php

namespace App\Controllers\Teacher;

use App\Controllers\Services\Redirector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SlimSession\Helper as Session;
use App\Models\Attandence;
use Psr\Http\Message\ServerRequestInterface;


class Export_singlestudent_attendancereport
{
    public Spreadsheet $spreadsheet;
    public Session $session;

    public function __construct(Spreadsheet $spreadsheet, Session $session)
    {
        $this->spreadsheet = $spreadsheet;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, $response, $args)
    {
        $userid = $this->session->get('userid');
        if (!$userid) {
            Redirector::redirect_to('/login');
        }

        // Fetch attendance data
        $queryParams = $request->getQueryParams();
        $studentid   = $queryParams['studentid'] ?? null;
        $courseid    = $queryParams['courseid'] ?? null;
        $attendancemodel = Attandence::where('studentid', $studentid)
            ->where('courseid', $courseid)
            ->where('userid', $userid)
            ->with(['student', 'course'])
            ->get();

        $sheet = $this->spreadsheet->getActiveSheet();

        // Headers
        $headers = ['#', 'Student ID', 'Student Fullname', 'Course Name', 'Present', 'Absent', 'Attendance Marks'];

        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        // Write attendance rows
        $row = 2;
        foreach ($attendancemodel as $index => $attendance) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $attendance->studentid);
            $sheet->setCellValue("C{$row}", $attendance->student->studentname);
            $sheet->setCellValue("D{$row}", $attendance->course->coursename);
            $sheet->setCellValue("E{$row}", $attendance->present == 1 ? 'yes' : 'no');
            $sheet->setCellValue("F{$row}", $attendance->absent == 1 ? 'yes' : 'no');
            $sheet->setCellValue("G{$row}", $attendance->attandence_marks);

            $row++;
        }

        // Output Excel file to browser
        $filename = "single_student_attendance_report.xlsx";

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: public");

        $writer = new Xlsx($this->spreadsheet);
        $writer->save("php://output");
        exit;
    }
}
