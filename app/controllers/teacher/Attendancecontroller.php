<?php

namespace App\Controllers\Teacher;

use Slim\Views\Twig;
use App\Models\Course;
use App\Models\Student;
use App\Models\Attandence;
use SlimSession\Helper as Session;
use App\Controllers\Services\Toast;


use Psr\Http\Message\ResponseInterface;
use App\Controllers\Services\Redirector;
use App\Controllers\Services\CsrfService;
use Psr\Http\Message\ServerRequestInterface;

class Attendancecontroller
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

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $toast = new Toast();
        $current_page = 'dashboard.attendance';

        // Use Eloquent pagination
        $coursemodel = Course::where('userid', $this->session->get('userid'))->get();
        // $success_msg = $this->session->get('success_msg'); // this session variables holds the success message on post request when course is save to db
        // $this->session->delete('success_msg');
        return $this->view->render(
            $response,
            'teacher/attendance/attendance.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'toast' => $toast,
                'csrf_token' => $this->csrf->generateToken()
            ]
        );
    }

    public function showallattendance(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $errors = [];
        $data = $request->getParsedBody();
        $coursename = trim($data['coursename']); # coursename contains courseid of selected coursename
        $csrf_token = trim($data['csrf_token']);
        $coursemodel = Course::where('userid', $this->session->get('userid'))->get();
        $toast = new Toast();
        $current_page = 'dashboard.attendance';

        $userid = $this->session->get('userid');

        if (!$userid) {
            Redirector::redirect_to('/login');
        }


        if (!$this->csrf->verify($csrf_token)) {
            $errors = ['csrf_token' => 'Invalid CSRF token. Please try again.'];
        } else if (empty($coursename)) {
            $errors = ['coursename' => 'Please Select Specific Coursename.'];
        }

        if (!empty($errors)) {
            return  $this->view->render(
                $response,
                'teacher/attendance/attendance.twig',
                [
                    'session' => $this->session,
                    'current_page' => $current_page,
                    'coursemodel' => $coursemodel,
                    'toast' => $toast,
                    'csrf_token' => $this->csrf->getToken(),
                    'errors' => $errors
                ]
            );
        }


        $attedancemodel = Attandence::where('courseid', $coursename)
            ->where('userid', $userid)->get();

        return $this->view->render(
            $response,
            'teacher/attendance/attendance.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'toast' => $toast,
                'csrf_token' => $this->csrf->getToken(),
                'attendancemodel' => $attedancemodel
            ]
        );
    }


    public function showstudentdata_forspecific_course_for_marking_attendance(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');
        $data = $request->getQueryParams();
        $courseid = isset($data['courseid']) ? $data['courseid'] : null;
        if (empty($courseid) || $courseid == null) {
            $response->getBody()->write(json_encode('Anauthorized'));
        } else {
            $studentmodel = Student::where('courseid', $courseid)
                ->where('userid', $userid)->with('course')->get();
            $response->getBody()->write(json_encode($studentmodel));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
