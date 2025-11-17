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
        $success = $this->session->get('success');
        $error = $this->session->get('error');

        $this->session->delete('success');
        $this->session->delete('error');

        return $this->view->render(
            $response,
            'teacher/attendance/attendance.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'toast' => $toast,
                'csrf_token' => $this->csrf->generateToken(),
                'error' => $error,
                'success' => $success
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


        $attendancemodel = Attandence::where('courseid', $coursename)
            ->where('userid', $userid)
            ->with(['student', 'course'])
            ->select('studentid', 'courseid')
            ->selectRaw('SUM(attandence_marks) as totalAttendancemarks')
            ->groupBy('studentid', 'courseid')
            ->get();

        return $this->view->render(
            $response,
            'teacher/attendance/attendance.twig',
            [
                'session' => $this->session,
                'current_page' => $current_page,
                'coursemodel' => $coursemodel,
                'toast' => $toast,
                'csrf_token' => $this->csrf->getToken(),
                'attendancemodel' => $attendancemodel
            ]
        );
    }


    //=========== Ajax method (used to display all student data  of specific course so that teacher can mark attendance)
    public function showstudentdata_forspecific_course_for_marking_attendance(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }
        $data = $request->getQueryParams();
        $courseid = isset($data['courseid']) ? $data['courseid'] : null;
        if (empty($courseid) || $courseid == null) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        } else {

            // $page = $request->getQueryParams()['page'] ?? 1;
            $studentmodel = Student::where('courseid', $courseid)
                ->where('userid', $userid)->with('course')->get();
            // ->where('userid', $userid)->with('course')->paginate(10, ['*'], 'page', $page);
            $response->getBody()->write(json_encode($studentmodel));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Ajax method (is responsible to save marked attendance for specific course)
    public function store_markedattendance(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }

        $data = $request->getParsedBody();

        $studentid         = $data['studentid'] ?? [];
        $coursename        = $data['coursename'] ?? [];
        $courseid          = $data['courseid'] ?? [];
        $attendance_marks  = $data['attendance_marks'] ?? [];
        $attendance_status = $data['attendance_status'] ?? [];

        $errors = [];
        $saved  = [];

        foreach ($studentid as $index => $id) {

            // VALIDATION
            if (empty($studentid)) {
                $errors[] = "emptystudentid";
                break;
            } else if (empty($coursename[$index])) {
                $errors[] = "emptycoursename";
                break;
            } else if (!isset($attendance_marks[$index]) || $attendance_marks[$index] === '') {
                $errors[] = "emptyattendance_marks";
                break;
            } else if (empty($attendance_status[$index])) {
                $errors[] = "emptyattendance_status";
                break;
            }

            // STATUS LOGIC
            $status = strtolower($attendance_status[$index]);

            $present = in_array($status, ['present', 'sick', 'excused']) ? 1 : 0;
            $absent  = ($status === 'absent') ? 1 : 0;

            // MARKS LOGIC
            $finalMarks = ($status === 'absent') ? 0 : (int)$attendance_marks[$index];

            // INSERT
            Attandence::create([
                'studentid'        => $id,
                'courseid'         => $courseid[$index],
                'userid'           => $userid,
                'present'          => $present,
                'absent'           => $absent,
                'attandence_marks' => $finalMarks,
            ]);

            $saved[] = 'success';
        }

        $result = [
            'saved'  => $saved,
            'errors' => $errors,
        ];

        $response->getBody()->write(json_encode($result));



        return $response->withHeader('Content-Type', 'application/json');
    }


    // Ajax method (is responsible to display attendance information related to specific student)
    public function display_single_student_attendance_information(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }
        $data = $request->getQueryParams();
        $studentid = trim($data['studentid']);
        $courseid = trim($data['courseid']);

        $attendancemodel = Attandence::where('courseid', $courseid)
            ->where('studentid', $studentid)->where('userid', $userid)->with('course')
            ->with('student')->get();

        $response->getBody()->write(json_encode($attendancemodel ?? ''));

        return $response->withHeader('Content-Type', 'application/json');
    }
    // Ajax method (is reponsible to update single student attendance data)
    public function update_singlestudent_attendancedata(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }
        $responsedata = [];
        $data = $request->getQueryParams();
        $attendanceid = $data['attendanceid'];
        $present = $data['present'];
        $absent = $data['absent'];
        $attendance_marks = $data['attendance_marks'];

        if (empty($attendanceid)) {
            $responsedata = ['emptyattendanceid'];
        } else if ($attendance_marks === '' || $attendance_marks === null) {
            $responsedata = ['emptyattendancemarks'];
        }
        Attandence::where('id', $attendanceid)
            ->where('userid', $userid)->update([
                'present' => $present,
                'absent' => $absent,
                'attandence_marks' => $attendance_marks
            ]);

        $responsedata = ['success'];

        $response->getBody()->write(json_encode($responsedata));
        return $response->withHeader('Content-Type', 'application/json');
    }
    // Ajax method (its responsible to delete single student attednance information)
    public function delete_singlestudent_attendance_information(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }
        $errors = [];
        $data = $request->getQueryParams();
        $attendanceid = $data['attendanceid'];
        if (empty($attendanceid)) {
            $errors = ['emptyattendanceid'];
        }

        $attendancemodel = Attandence::where('id', $attendanceid)
            ->where('userid', $userid)->delete();

        if ($attendancemodel) {
            $errors = ['success'];
        } else {
            $errors = ['failed'];
        }
        $response->getBody()->write(json_encode($errors));
        return $response->withHeader('Content-Type', 'application/json');
    }



    public function deleteall_attendance_for_single_student(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $userid = $this->session->get('userid');

        if (!$userid) {
            $response->getBody()->write(json_encode('Anauthorized Action'));
        }
        $studentid = $args['attendanceid'];
        // dd($studentid);
        $attendancemodel = Attandence::where('studentid', $studentid)
            ->where('userid', $userid)->delete();

        if ($attendancemodel) {
            $this->session->set('success', 'Single Student Attendance Information Deleted Successully');
            return Redirector::redirect_to('/teacher/attendance');
        } else {
            $this->session->set('error', 'Failed To Delete Single Student Attendance Information');
            return Redirector::redirect_to('/teacher/attendance');
        }
    }
}
