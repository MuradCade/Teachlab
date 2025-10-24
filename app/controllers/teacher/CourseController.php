<?php

namespace App\Controllers\Teacher;

use Psr\Http\Message\ResponseInterface;

use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper as Session;
use App\Controllers\Services\CsrfService;
use App\Controllers\Services\Redirector;
use App\Controllers\Services\Toast;
use App\Models\Course;


use Slim\Views\Twig;

class CourseController
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
        $current_page = 'dashboard.course';
        // * Pagination code
        // Get query params for customization(url or address bar)
        $params = $request->getQueryParams();
        $perPage = isset($params['per_page']) ? (int)$params['per_page'] : 8;
        // * end pagination code
        // Use Eloquent pagination
        $coursemodel = Course::where('userid', $this->session->get('userid'))
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        $success_msg = $this->session->get('success_msg'); // this session variables holds the success message on post request when course is save to db
        $this->session->delete('success_msg');
        return $this->view->render(
            $response,
            'teacher/course/course.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'queryParams' => $params,
                'errors' => $success_msg,
                'toast' => $toast
            ]
        );
    }

    public function indexcreatecourse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $toast = new Toast();
        $token = $this->csrf->generateToken();
        $current_page = 'dashboard.course';
        $success_msg = $this->session->get('success_msg'); // this session variables holds the success message on post request when course is save to db
        $this->session->delete('success_msg');
        return $this->view->render(
            $response,
            'teacher/course/createcourse.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'toast' => $toast,
                'csrf_token' => $token,
                'errors' => $success_msg
            ]
        );
    }
    public function storeCourse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $errors = [];
        $data = $request->getParsedBody();
        $coursename = trim($data['coursename']);
        $csrf_token = trim($data['csrf_token']);
        $toast = new Toast();
        $userid = $this->session->get('userid');
        $current_page = 'dashboard.course';

        $token = $this->csrf->getToken();

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors['csrf_token'] = 'Invalid CSRF token. Please try again.';
        } else if (empty($coursename)) {
            $errors['coursename'] = 'Course Field is require';
        }

        if (!empty($errors)) {
            return $this->view->render(
                $response,
                'teacher/course/createcourse.twig',
                [
                    'session' => $this->session,
                    'current_page' => $current_page,
                    'csrf_token' => $token,
                    'errors' => $errors,
                    'toast' => $toast
                ]
            );
        }

        // save new course into course table in db
        $savnewcourse = Course::create([
            'coursename' => $coursename,
            'userid' => $userid
        ]);

        $this->session->set('success_msg', ['success' => 'Course Information saved successfully']);
        return Redirector::redirect_to('/teacher/course/createcourse');
    }


    public function editCourse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $toast = new Toast();
        $csrf_token = $this->csrf->generateToken();
        $current_page = 'dashboard.course';
        $courseid = base64_decode($args['courseid']);

        $coursemodel = Course::where('id', $courseid)
            ->where('userid', $this->session->get('userid'))
            ->first();

        return $this->view->render(
            $response,
            'teacher/course/course_edit.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'csrf_token' => $csrf_token,
                'toast' => $toast
            ]
        );
    }
    public function updateCourse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $erros = [];
        $toast = new Toast();
        $data = $request->getParsedBody();
        $courseid = base64_decode($args['courseid']);
        $csrf_token = trim($data['csrf_token']);
        $coursename = trim($data['coursename']);
        $token = $this->csrf->getToken();

        $current_page = 'dashboard.course';

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors['csrf_token'] = 'Invalid CSRF token. Please try again.';
        } else if (empty($coursename)) {
            $errors['coursename'] = 'Course Field is require';
        }

        if (!empty($errors)) {

            $coursemodel = Course::where('id', $courseid)
                ->where('userid', $this->session->get('userid'))
                ->first();

            return $this->view->render(
                $response,
                'teacher/course/course_edit.twig',
                [
                    'session' => $this->session,
                    'current_page' => $current_page,
                    'coursemodel' => $coursemodel,
                    'csrf_token' => $token,
                    'errors' => $errors,
                    'toast' => $toast
                ]
            );
        }

        $coursemodel = Course::where('id', $courseid)
            ->where('userid', $this->session->get('userid'))
            ->update([
                'coursename' => $coursename
            ]);
        $this->session->set('success_msg', ['success' => 'Course Information saved successfully']);
        return Redirector::redirect_to('/teacher/course');
    }

    public function deleteCourse(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $courseid = base64_decode($args['courseid']);


        Course::where('id', $courseid)
            ->where('userid', $this->session->get('userid'))
            ->delete();

        $this->session->set('success_msg', ['success' => 'Course Information deleted successfully']);
        return Redirector::redirect_to('/teacher/course');
    }
}
