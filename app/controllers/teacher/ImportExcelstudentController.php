<?php


namespace App\Controllers\Teacher;

use Slim\Views\Twig;

use Slim\Psr7\Stream;
use App\Models\Course;
use App\Models\Student;
use SlimSession\Helper as Session;
use App\Controllers\Services\Toast;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\Services\Redirector;
use App\Controllers\Services\CsrfService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Http\Message\ServerRequestInterface;
// use Illuminate\Database\QueryException;
// use Psr\Http\Message\RequestInterface;

class ImportExcelstudentController
{
    protected Twig $view;
    protected Session $session;
    protected CsrfService $csrf;
    protected Spreadsheet $spreadsheet;
    public function __construct(Twig $view, Session $session, CsrfService $csrf, Spreadsheet $spreadsheet)
    {
        $this->view = $view;
        $this->session = $session;
        $this->csrf = $csrf;
        $this->spreadsheet = $spreadsheet;
    }


    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Check if user is logged in
        $userid = $this->session->get('userid');
        if (!$userid) {
            // Redirect to login page if not logged in
            return Redirector::redirect_to('/login');
        }

        $toast = new Toast();
        $errors = [];
        $errors['excel_error'] = $this->session->get('excel_error') ?? null;
        $errors['coursename_error'] = $this->session->get('coursename_error') ?? null;
        $errors['success'] = $this->session->get('success') ?? null;
        $this->session->delete('excel_error');
        $this->session->delete('coursename_error');
        $this->session->delete('success');
        // Render the import Excel student view
        return $this->view->render($response, 'teacher/student/import_excel_student.twig', [
            'csrf_token' => $this->csrf->generateToken(),
            'session' => $this->session,
            'current_page' => 'dashboard.student',
            'errors' => $errors,
            'toast' => $toast


        ]);
    }


    public function store(ServerRequestInterface $request, ResponseInterface $response)
    {
        $userid = $this->session->get('userid');
        if (!$userid) {
            return Redirector::redirect_to('/login');
        }

        $errors = [];
        $data = $request->getParsedBody();
        $csrf_token = trim($data['csrf_token']);
        $datafile = $request->getUploadedFiles();
        $excelfile = $datafile['excelfile'] ?? null;

        $filename = $excelfile?->getClientFilename();
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $AllowedimageType = ['xlsx', 'xlsm'];

        if (!$this->csrf->verify($csrf_token)) {
            $errors['csrf_token'] = 'Invalid CSRF token. Please try again.';
        } elseif (!$excelfile || $excelfile->getError() === UPLOAD_ERR_NO_FILE) {
            $errors['excelfile'] = 'Please Upload Excel File';
        } elseif (!in_array($extension, $AllowedimageType)) {
            $errors['filetypenotsupported'] = 'Sorry, Uploaded File Not Supported';
        }

        if (!empty($errors)) {

            return $this->view->render($response, 'teacher/student/import_excel_student.twig', [
                'csrf_token' => $this->csrf->getToken(),
                'session' => $this->session,
                'current_page' => 'dashboard.student',
                'toast' => new Toast(),
                'errors' => $errors
            ]);
        }

        // Save uploaded file
        $createfilename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $filename);
        $targetpath = storage_path('importexcelfile/' . $createfilename);
        $excelfile->moveTo($targetpath);

        $spreadfile = IOFactory::load($targetpath);
        $rows = $spreadfile->getActiveSheet()->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1) continue; // skip header

            $studentid = trim($row['B']);
            $studentfullname = trim($row['C']);
            $coursename = trim($row['D']);

            if (empty($studentid) || empty($studentfullname) || empty($coursename)) {
                $this->session->set('excel_error', 'One or more required fields are empty inside the uploaded Excel file.');
                $this->session->delete('success');
                unlink($targetpath);

                return Redirector::redirect_to('/teacher/student/importfromexcel');
            }

            $student = Student::where('id', $studentid)
                ->where('userid', $userid)
                ->first();

            if ($student) {
                $student->update([
                    'studentname' => $studentfullname,
                ]);
                $this->session->set('success', "Student Data Imported Successsfully.");
                $this->session->delete('excel_error');
                $this->session->delete('coursename_error');
            } else {
                $course = Course::where('coursename', $coursename)
                    ->where('userid', $userid)
                    ->first();

                if (!$course) {
                    $this->session->set('coursename_error', "Coursename inside the uploaded excel file doesn't exist.");
                    $this->session->delete('success');
                    unlink($targetpath);
                    return Redirector::redirect_to('/teacher/student/importfromexcel');
                }

                Student::create([
                    'id' => $studentid,
                    'userid' => $userid,
                    'studentname' => $studentfullname,
                    'courseid' => $course->id,
                ]);
                $this->session->set('success', "Student Data Imported Successsfully.");
                $this->session->delete('excel_error');
                $this->session->delete('coursename_error');
            }
        }

        unlink($targetpath);

        // ✅ Always return a response
        return Redirector::redirect_to('/teacher/student/importfromexcel');
    }
}
