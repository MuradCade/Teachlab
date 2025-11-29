<?php

namespace App\Controllers\Teacher;

use Psr\Http\Message\ResponseInterface;

use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper as Session;
use App\Controllers\Services\CsrfService;
use App\Controllers\Services\Toast;
use App\Models\Course;
use App\Models\Student;
use Slim\Views\Twig;
use App\Controllers\Services\Redirector;
use Illuminate\Database\QueryException;

class StudentController
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
        $current_page = "dashboard.student";
        $coursemodel = Course::where(
            "userid",
            $this->session->get("userid"),
        )->get();
        $errors = $this->session->get("success_msg");
        $this->session->delete("success_msg");
        return $this->view->render($response, "teacher/student/student.twig", [
            "session" => $this->session,
            "current_page" => $current_page,
            "csrf_token" => $this->csrf->generateToken(),
            "coursemodel" => $coursemodel,
            "errors" => $errors,
            "toast" => $toast,
        ]);
    }
    public function indexAddnewstudent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $success_msg = $this->session->get("success_msg");
        $this->session->delete("success_msg"); // delete success message after displaying it once
        $toast = new Toast();
        $userid = $this->session->get("userid");
        $token = $this->csrf->generateToken();
        // we fetching course table so each student will be part of specific course
        $coursemodel = Course::where("userid", $userid)->get();
        $current_page = "dashboard.student";
        return $this->view->render(
            $response,
            "teacher/student/addstudent.twig",
            [
                "session" => $this->session,
                "current_page" => $current_page,
                "csrf_token" => $token,
                "toast" => $toast,
                "coursedata" => $coursemodel,
                "errors" => $success_msg,
            ],
        );
    }
    public function storenewStudentData(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ) {
        $userid = $this->session->get("userid");
        $toast = new Toast();
        $errors = [];
        $data = $request->getParsedBody();
        $csrf_token = trim($data["csrf_token"]);
        $studentid = trim($data["studentid"]);
        $studentfullname = trim($data["studentfullname"]);
        $coursename = trim($data["coursename"]);
        $token = $this->csrf->getToken();

        // we fetching course table so each student will be part of specific course
        $coursemodel = Course::where("userid", $userid)->get();
        $current_page = "dashboard.student";

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors["csrf_token"] = "Invalid CSRF token. Please try again.";
        } elseif (empty($studentid)) {
            $errors["studentid"] = "Student Id field is require";
        } elseif (empty($studentfullname)) {
            $errors["studentfullname"] = "Student Name field is require";
        } elseif (empty($coursename)) {
            $errors["coursename"] = "Select Coursename Name";
        }

        if (!empty($errors)) {
            return $this->view->render(
                $response,
                "teacher/student/addstudent.twig",
                [
                    "session" => $this->session,
                    "current_page" => $current_page,
                    "csrf_token" => $token,
                    "toast" => $toast,
                    "coursedata" => $coursemodel,
                    "old" => $data,
                    "errors" => $errors,
                ],
            );
        }

        try {
            Student::create([
                "id" => $studentid,
                "studentname" => $studentfullname,
                "courseid" => $coursename, // this is an id of the course not the actual coursename
                "userid" => $userid,
            ]);
            $this->session->set("success_msg", [
                "success" => "Course Information saved successfully",
            ]);
            return Redirector::redirect_to("/teacher/student/addnewstudent");
        } catch (QueryException $e) {
            // Handle duplicate entry error, assuming error code 1062 for duplicate entry
            if ($e->errorInfo[1] == 1062) {
                $this->session->set("success_msg", [
                    "duplocated_studentid" =>
                    "Student ID already exists. Please use a different Student ID.",
                ]);
                return Redirector::redirect_to(
                    "/teacher/student/addnewstudent",
                );
            }
        }
    }

    public function fetchstudentAssociatedwithspecific_course(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $errors = [];
        $toast = new Toast();
        $data = $request->getParsedBody();
        $coursename = trim($data["coursename"]);
        $csrf_token = trim($data["csrf_token"]);
        $current_page = "dashboard.student";
        $coursemodel = Course::where(
            "userid",
            $this->session->get("userid"),
        )->get();

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors["csrf_token"] = "Invalid CSRF token. Please try again.";
        } elseif (empty($coursename)) {
            $errors["coursename"] =
                "Coursename is required to display associated students";
        }

        // $studentmodel = Student::where('courseid',)

        if (!empty($errors)) {
            return $this->view->render(
                $response,
                "teacher/student/student.twig",
                [
                    "session" => $this->session,
                    "current_page" => $current_page,
                    "csrf_token" => $this->csrf->getToken(),
                    "coursemodel" => $coursemodel,
                    "errors" => $errors,
                    "toast" => $toast,
                ],
            );
        }

        // get courseid from course table
        $getcourseid = Course::where("coursename", $coursename)
            ->where("userid", $this->session->get("userid"))
            ->get();

        $studentmodel = Student::where("courseid", $getcourseid[0]["id"])
            ->where("userid", $this->session->get("userid"))
            ->get();

        return $this->view->render($response, "teacher/student/student.twig", [
            "session" => $this->session,
            "current_page" => $current_page,
            "csrf_token" => $this->csrf->getToken(),
            "coursemodel" => $coursemodel,
            "studentmodel" => $studentmodel,
            "toast" => $toast,
        ]);
    }

    public function indexEditStudent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $studentid = $args["studentid"];
        // dd($studentid);
        $toast = new Toast();
        $current_page = "dashboard.student";
        $studentmodel = Student::where("id", $studentid)
            ->where("userid", $this->session->get("userid"))
            ->first();

        if ($studentmodel == null) {
            // student id not found in the database
            return Redirector::redirect_to("/teacher/student");
        }

        $coursemodel = Course::where(
            "userid",
            $this->session->get("userid"),
        )->get();
        $errors = $this->session->get("success_msg");
        $this->session->delete("success_msg");
        return $this->view->render(
            $response,
            "teacher/student/editstudent.twig",
            [
                "session" => $this->session,
                "current_page" => $current_page,
                "csrf_token" => $this->csrf->generateToken(),
                "toast" => $toast,
                "studentdata" => $studentmodel,
                "coursemodel" => $coursemodel,
                "errors" => $errors,
            ],
        );
    }

    public function UpdateStudent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ) {
        $errors = [];
        $toast = new Toast();
        $studentid_url = $args["studentid"];
        $data = $request->getParsedBody();
        $csrf_token = trim($data["csrf_token"]);
        $studentid = trim($data["studentid"]);
        $studentname = trim($data["studentname"]);
        $coursename = trim($data["coursename"]);
        $studentmodel = Student::where("id", $studentid_url)
            ->where("userid", $this->session->get("userid"))
            ->first();

        // dd($data);
        if ($studentmodel == null) {
            //     // student id not found in the database
            return Redirector::redirect_to("/teacher/student");
        }

        $coursemodel = Course::where(
            "userid",
            $this->session->get("userid"),
        )->get();
        if (!$this->csrf->verify($csrf_token)) {
            $errors["csrf_token"] = "Invalid CSRF token. Please try again.";
        } elseif (empty($studentid)) {
            $errors["studentid"] = "Student Id field is require";
        } elseif (empty($studentname)) {
            $errors["studentname"] = "Student Name field is require";
        } elseif (empty($coursename)) {
            $errors["coursename"] = "Select Coursename Name";
        }

        if (!empty($errors)) {
            return $this->view->render(
                $response,
                "teacher/student/editstudent.twig",
                [
                    "current_page" => "dashboard.student",
                    "csrf_token" => $this->csrf->getToken(),
                    "toast" => $toast,
                    "studentdata" => $studentmodel,
                    "coursemodel" => $coursemodel,
                    "errors" => $errors,
                    "session" => $this->session,
                ],
            );
        }

        Student::where("id", $studentid_url)
            ->where("userid", $this->session->get("userid"))
            ->update([
                "id" => $studentid,
                "studentname" => $studentname,
                "courseid" => $coursename,
            ]);

        $this->session->set("success_msg", [
            "success" => "Student Information Updated Successfully.",
        ]);
        return Redirector::redirect_to(
            "/teacher/student/edit/" . $studentid,
        );
    }
    public function DeleteStudent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ) {
        $studentid = $args["studentid"];
        Student::where("id", $studentid)
            ->where("userid", $this->session->get("userid"))
            ->delete();
        $this->session->set("success_msg", [
            "success" => "Student Information Deleted Successfully.",
        ]);

        return Redirector::redirect_to("/teacher/student");
    }
}
