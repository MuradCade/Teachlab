<?php

namespace App\Controllers\Teacher;

use Slim\Views\Twig;
use App\Models\Course;
use SlimSession\Helper as Session;
use App\Controllers\Services\Toast;

use Psr\Http\Message\ResponseInterface;
use App\Controllers\Services\Redirector;
use App\Controllers\Services\CsrfService;
use App\Models\Assignmentform;
use Psr\Http\Message\ServerRequestInterface;

class Assignmentcontroller
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
        $userid = $this->session->get("userid");
        if (!$userid) {
            return Redirector::redirect_to("/login");
        }
        // * Pagination code
        // Get query params for customization(url or address bar)
        $params = $request->getQueryParams();
        $perPage = isset($params["per_page"]) ? (int) $params["per_page"] : 6;
        // * end pagination code
        // Use Eloquent pagination
        //
        $current_page = "dashboard.assignment";
        $assignmentform_model = Assignmentform::where("userid", $userid)
            ->with("course")
            ->orderBy("created_at", "asc")
            ->paginate($perPage);
        $success = $this->session->get('success');
        $this->session->delete('success');
        return $this->view->render(
            $response,
            "teacher/assignment/assignment.twig",
            [
                "session" => $this->session,
                "current_page" => $current_page,
                "assignmentformmodel" => $assignmentform_model,
                "queryParams" => $params,
                'success' => $success,
                'toast' => $toast
            ],
        );
    }
    public function create(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $userid = $this->session->get("userid");
        if (!$userid) {
            return Redirector::redirect_to("/login");
        }
        $toast = new Toast();
        // storing session flash messages
        $sessions = [];
        $sessions["success"] = $this->session->get("success");
        $sessions["failed"] = $this->session->get("failed");
        //deleting session flash messages
        $this->session->delete("success");
        $this->session->delete("failed");

        $current_page = "dashboard.assignment";
        $coursemodel = Course::where("userid", $userid)->get();
        return $this->view->render(
            $response,
            "teacher/assignment/createassignment.twig",
            [
                "session" => $this->session,
                "current_page" => $current_page,
                "coursemodel" => $coursemodel,
                "csrf_token" => $this->csrf->generateToken(),
                "sessions" => $sessions,
                "toast" => $toast,
            ],
        );
    }

    public function store(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $userid = $this->session->get("userid");
        if (!$userid) {
            return Redirector::redirect_to("/login");
        }
        $toast = new Toast();
        $errors = [];
        $data = $request->getParsedBody();
        $assignment_title = trim($data["assignment_title"]);
        $assignment_content = trim($data["assignment_content"]);
        $assignment_coursename = trim($data["assignment_coursename"]);
        $assignment_filetype = trim($data["assignment_filetype"]);
        $assignment_marks = trim($data["assignment_marks"]);
        $assignment_deadline = trim($data["assignment_deadline"]);
        $assignment_status = trim($data["assignment_status"]);
        $csrf_token = $data["csrf_token"];

        if (!$this->csrf->verify($csrf_token)) {
            $errors["csrf_token"] = "Invalid CSRF token. Please try again.";
        } elseif (empty($assignment_title)) {
            $errors["assignment_title"] = "Assignment Title Field is required.";
        } elseif (empty($assignment_content)) {
            $errors["assignment_content"] =
                "Assignment Content Field is required.";
        } elseif (empty($assignment_coursename)) {
            $errors["assignment_coursename"] = "Coursename Field is required.";
        } elseif (empty($assignment_filetype)) {
            $errors["assignment_filetype"] =
                "Assignmen Allowed Filetype Field is required.";
        } elseif (empty($assignment_marks)) {
            $errors["assignment_marks"] = "Assignmen Marks Field is required.";
        } elseif (empty($assignment_status)) {
            $errors["assignment_status"] =
                "Assignmen Status Field is required.";
        }

        $current_page = "dashboard.assignment";
        $coursemodel = Course::where("userid", $userid)->get();

        if (!empty($errors)) {
            return $this->view->render(
                $response,
                "teacher/assignment/createassignment.twig",
                [
                    "session" => $this->session,
                    "current_page" => $current_page,
                    "coursemodel" => $coursemodel,
                    "csrf_token" => $this->csrf->generateToken(),
                    "toast" => $toast,
                    "errors" => $errors,
                    "old" => $data,
                ],
            );
        }


        $assignmentform_model = Assignmentform::create([
            "title" => $assignment_title,
            "content" => $assignment_content,
            "courseid" => $assignment_coursename,
            "allowed_filetype" => $assignment_filetype,
            "marks" => $assignment_marks,
            "status" => $assignment_status,
            "deadline_date" => $assignment_deadline ?? null,
            "userid" => $userid,
        ]);

        if ($assignmentform_model) {
            $this->session->set(
                "success",
                "Assignment Form Created Successfully",
            );

            return Redirector::redirect_to(
                "/teacher/assignment/createassignment",
            );
        }

        $this->session->set("failed", "Assignment Form Failed To Be Created");
        return Redirector::redirect_to("/teacher/assignment/createassignment");
    }


    // display assignment details with ajax
    public function displayassignmentdetails(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $userid = $this->session->get('userid');
        $data = $request->getQueryParams();
        $assignmentid = $data['assignmentid'];
        $assignmentmodel = Assignmentform::where('assignmentformid', $assignmentid)
            ->where('userid', $userid)->with('course')->get();
        $response->getBody()->write(json_encode($assignmentmodel));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function editassignmentform(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $toast = new Toast();

        $userid = $this->session->get('userid');
        $success = $this->session->get('success');
        $this->session->delete('success');
        if (!$userid) {
            return Redirector::redirect_to('/login');
        }
        $assignmentid = $args['assignmentid'];

        $assignmentform_model = Assignmentform::where('assignmentformid', $assignmentid)
            ->where('userid', $userid)->with('course')->first();
        $coursemodel = Course::where('userid', $userid)->get();

        if (!$assignmentform_model) {
            return Redirector::redirect_to('/teacher/assignment');
        }
        $current_page = "dashboard.assignment";

        // dd($assignmentform_model);
        return $this->view->render(
            $response,
            "teacher/assignment/editassignment.twig",
            [
                "session" => $this->session,
                "current_page" => $current_page,
                "csrf_token" => $this->csrf->generateToken(),
                'assignmentmodel' => $assignmentform_model,
                'coursemodel' => $coursemodel,
                'success' => $success,
                'toast' => $toast
            ],
        );
    }
    public function updateassignmentform(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $toast = new Toast();
        $userid = $this->session->get('userid');
        $assignmentid = $args['assignmentid'];

        $errors = [];
        $data = $request->getParsedBody();
        $assignment_title = $data['assignment_title'];
        $csrf_token = $data['csrf_token'];
        $assignment_content = $data['assignment_content'];
        $assignment_coursename = $data['assignment_coursename'];
        $assignment_marks = $data['assignment_marks'];
        $assignment_deadline = $data['assignment_deadline'];
        $assignment_status = $data['assignment_status'];

        $assignmentform_model = Assignmentform::where('assignmentformid', $assignmentid)
            ->where('userid', $userid)->with('course')->first();
        $coursemodel = Course::where('userid', $userid)->get();

        if (!$assignmentform_model) {
            return Redirector::redirect_to('/teacher/assignment');
        }

        if (!$this->csrf->verify($csrf_token)) {
            $errors["csrf_token"] = "Invalid CSRF token. Please try again.";
        } else if (empty($assignment_title)) {
            $errors["assignment_title"] = "Assignment Title Field is required.";
        } else if (empty($assignment_content)) {
            $errors["assignment_content"] = "Assignment Content Field is required.";
        } else if (empty($assignment_coursename)) {
            $errors["assignment_coursename"] = "Assignment Coursename Field is required.";
        } else if (empty($assignment_marks)) {
            $errors["assignment_marks"] = "Assignment Marks Field is required.";
        } else if (empty($assignment_status)) {
            $errors["assignment_status"] = "Assignment Status Field is required.";
        }
        $current_page = "dashboard.assignment";
        if (!empty($errors)) {
            return $this->view->render(
                $response,
                "teacher/assignment/editassignment.twig",
                [
                    "session" => $this->session,
                    "current_page" => $current_page,
                    "csrf_token" => $this->csrf->getToken(),
                    'assignmentmodel' => $assignmentform_model,
                    'coursemodel' => $coursemodel,
                    'errors' => $errors,
                    'toast' => $toast
                ],
            );
        }


        $updated_assignmentformmodel = Assignmentform::where('assignmentformid', $assignmentid)
            ->where('userid', $userid)
            ->update([
                'title' => $assignment_title,
                'content' => $assignment_content,
                'courseid' => $assignment_coursename,
                'marks' => $assignment_marks,
                'deadline_date' => $assignment_deadline ?? null,
                'status' => $assignment_status
            ]);
        $this->session->set('success', 'Assignment Form Information Updated Successfully');
        return Redirector::redirect_to("/teacher/assignment/{$assignmentid}/edit");
    }




    public function deleteassignmentform(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) {
        $assignmentid = $args['assignmentid'];
        Assignmentform::where('assignmentformid', $assignmentid)->delete();

        $this->session->set('success', 'Assignment Form Information Deleted Successfully');
        return Redirector::redirect_to("/teacher/assignment");
    }
}
