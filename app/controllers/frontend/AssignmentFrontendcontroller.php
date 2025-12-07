<?php

namespace App\Controllers\frontend;

use Dompdf\Dompdf;
use Slim\Views\Twig;
use App\Models\Course;
use App\Models\Assignmentform;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Assignmententries;
use SlimSession\Helper as Session;
use App\Controllers\Services\Toast;

use Psr\Http\Message\ResponseInterface;
use App\Controllers\Services\Redirector;
use App\Controllers\Services\CsrfService;
use Psr\Http\Message\ServerRequestInterface;

class AssignmentFrontendcontroller
{
    protected Twig $view;
    protected Session $session;
    protected CsrfService $csrf;
    public function __construct(Twig $view, Session $session, CsrfService $csrf)
    {
        $this->view = $view;
        $this->session = $session;
        $this->csrf = $csrf;
    }


    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $toast = new Toast();
        $getassignmentid = strtoupper($args['assignmentid']);
        $decoded_assignmentid = base64_decode($getassignmentid);
        $assignmentmodel = Assignmentform::where('assignmentformid', $decoded_assignmentid)
            ->with('course')->first();
        return $this->view->render(
            $response,
            "frontend/assignment_frontend/assignmentfrontend.twig",
            [
                'assignmentmodel' => $assignmentmodel,
                'toast' => $toast
            ]
        );
    }


    public function store(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {

        $data = $request->getParsedBody();

        // Extract input
        $courseid          = trim($data['courseid'] ?? '');
        $assignmentformid  = trim($data['assignmentformid'] ?? '');
        $studentid         = trim($data['studentid'] ?? '');
        $studentfullname   = trim($data['studentfullname'] ?? '');
        $assignment_allowed_filetype   = trim($data['assignment_allowed_filetype'] ?? '');
        $assignment_marks   = trim($data['assignment_marks'] ?? '');

        // define the allowed files 
        $allowedfiles = [
            "word_document" => ['doc', 'docx']
        ];

        // fetch all data related to currently submissing student so its id can be checked if it already submitted the assignment
        $existing = Assignmententries::where('studentid', $studentid)
            ->where('assignmentformid', $assignmentformid)
            ->first();


        // step1: Validate empty fields
        if (empty($courseid)) {
            return $this->json($response, [
                'error' => 'Missing Courseid, please try again later'
            ]);
        } else if (empty($assignmentformid)) {
            return $this->json($response, [
                'error' => 'Missing Formid, please try again later'
            ]);
        } else if (empty($studentid)) {
            return $this->json($response, [
                'error' => 'Student id is required'

            ]);
        } else if ($existing) {
            return $this->json($response, [
                'error' => 'You have already submitted this assignment.'
            ]);
        } else if (empty($studentfullname)) {
            return $this->json($response, [
                'error' => 'Student fullname is required'
            ]);
        } else if (empty($assignment_allowed_filetype)) {
            return $this->json($response, [
                'error' => 'Missing Assignment Allowed Filetype, please try again later'
            ]);
        } else if (empty($assignment_marks)) {
            return $this->json($response, [
                'error' => 'Missing Assignment Marks, please try again later'
            ]);
        }






        // File validation
        $files = $request->getUploadedFiles();
        $assignmentfile = $files['assignmentfile'] ?? null;

        if (!$assignmentfile || $assignmentfile->getError() === UPLOAD_ERR_NO_FILE) {
            return $this->json($response, [
                'error' => 'Assignment File is required'
            ]);
        }

        if ($assignmentfile->getError() !== UPLOAD_ERR_OK) {
            return $this->json($response, [
                'error' => 'File upload failed.'
            ]);
        }



        // Sanitize filename: replace spaces and symbols with _
        $originalName = $assignmentfile->getClientFilename();
        // get uploaded file extension
        $extension =  strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        // uploaded file size 
        $fileSize = $assignmentfile->getSize(); // size in bytes
        // keeps letters, numbers, dots, dashes — everything else becomes "_"
        $cleanName =  preg_replace('/[^A-Za-z0-9.]/', '_', $originalName);
        // add timestamp to make filename unique
        $uniqueName = time() . '_' . $cleanName;


        // step2 : validate if the uploaded assignment file extension is allowed
        if (!in_array($extension, $allowedfiles[$assignment_allowed_filetype])) {
            return $this->json($response, [
                'error' => 'Uploaded assignment file is not allowed,please upload proper assignment file'
            ]);
        }

        // step3: store the original assignment file inside the uploads/original folder
        $upload_original = storage_path('uploads/original/');

        try {
            $assignmentfile->moveTo($upload_original . '/' . $uniqueName);
        } catch (\Exception $e) {
            return $this->json($response, [
                'error' => 'Failed to store uploaded file: ' . $e->getMessage()
            ]);
        }

        // step4: generate uploaded file url
        $fileurl = storage_url('uploads/original/' . $uniqueName);

        // step5: convert word document file into pdf
        $pdfName = pathinfo($uniqueName, PATHINFO_FILENAME) . '.pdf';
        $pdfPath = storage_path('uploads/pdf/') . '/' . $pdfName;

        try {
            // Set PDF renderer (DomPDF)
            Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            Settings::setPdfRendererPath(__DIR__ . '/../../vendor/dompdf/dompdf'); // adjust path if needed

            // Load Word document
            $phpWord = IOFactory::load($upload_original . '/' . $uniqueName, 'Word2007');

            // Save as HTML (intermediate)
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            ob_start();
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean();

            // Render PDF via DomPDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Save PDF to file
            file_put_contents($pdfPath, $dompdf->output());

            // Generate PDF public URL
            $pdfUrl = storage_url('uploads/pdf/' . $pdfName);
        } catch (\Exception $e) {
            return $this->json($response, [
                'error' => 'Failed to convert Word to PDF: ' . $e->getMessage()
            ]);
        }

        // step6: store assignment submission
        Assignmententries::create([
            'studentid' => $studentid,
            'studentfullname' => $studentfullname,
            'courseid' => $courseid,
            'uploaded_filename' => $uniqueName,
            'pdf_filename' => $pdfName,
            'assignmentformid' => $assignmentformid,
            'uploaded_filesize' => $fileSize,
            'marks' => $assignment_marks,
        ]);

        return $this->json($response, [
            'success' => true,
            'message' => 'Assignment submitted successfully.'

        ]);
    }


    // Helper to return JSON
    private function json(ResponseInterface $response, array $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
