<?php

use Slim\App;
// use App\Config\Logger;
use App\Middlewares\AuthMiddleware;
// use App\Middlewares\GuestMiddleware;
// use App\Middlewares\MaintainanceMode;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Services\Redirector;
// use App\Middlewares\RolebasedMiddleware;
use App\Middlewares\RemembermeMiddleware;
// use App\Controllers\Services\PHPMAILService;
use App\Controllers\Teacher\Assignmentcontroller;
use App\Controllers\Teacher\Attendancecontroller;
// use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\Teacher\Exportall_attendancedata;
// use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Teacher\ImportExcelstudentController;
use App\Controllers\Teacher\GenerateExcelTemplateController;
use App\Controllers\Teacher\Export_singlestudent_attendancereport;

return function (App $app) {
    #Teacher route starts here
    $app->group("/teacher", function (RouteCollectorProxy $group) {
        // redirect /teacher → /teacher/dashboard
        $group
            ->get("/", function () {
                return Redirector::redirect_to("/teacher/dashboard");
            })
            ->setName("teacher.redirect");

        // teacher dashboard home
        $group
            ->get("/dashboard", [
                \App\Controllers\Teacher\TeacherController::class,
                "dashboardHome",
            ])
            ->setName("teacher.dashboard.home");

        // teacher logout
        $group
            ->get("/logout", [
                \App\Controllers\Teacher\TeacherController::class,
                "logout",
            ])
            ->setName("teacher.logout");

        //======= Course Routes Starts Here.

        $group
            ->get("/course", [
                \App\Controllers\Teacher\CourseController::class,
                "index",
            ])
            ->setName("teacher.course.index");

        $group
            ->get("/course/createcourse", [
                \App\Controllers\Teacher\CourseController::class,
                "indexcreatecourse",
            ])
            ->setName("teacher.course.createcourse.index");

        $group
            ->post("/course/createcourse", [
                \App\Controllers\Teacher\CourseController::class,
                "storeCourse",
            ])
            ->setName("teacher.course.createcourse.submit");

        $group
            ->get("/course/edit/{courseid}", [
                \App\Controllers\Teacher\CourseController::class,
                "editCourse",
            ])
            ->setName("teacher.course.course_edit.index");

        $group
            ->post("/course/edit/{courseid}/update", [
                \App\Controllers\Teacher\CourseController::class,
                "updateCourse",
            ])
            ->setName("teacher.course.course_edit.update.submit");

        $group
            ->get("/course/createcourse/edit/{courseid}/delete", [
                \App\Controllers\Teacher\CourseController::class,
                "deleteCourse",
            ])
            ->setName("teacher.course.course_edit.delete");

        //=========== Course Routes Ends Here. ===================

        //  ============= student routes starts here
        $group
            ->get("/student", [
                \App\Controllers\Teacher\StudentController::class,
                "index",
            ])
            ->setName("teacher.student.index");

        $group
            ->post("/student", [
                \App\Controllers\Teacher\StudentController::class,
                "fetchstudentAssociatedwithspecific_course",
            ])
            ->setName("teacher.student.submit");

        $group
            ->get("/student/addnewstudent", [
                \App\Controllers\Teacher\StudentController::class,
                "indexAddnewstudent",
            ])
            ->setName("teacher.student.addnewstudent.index");

        $group
            ->post("/student/addnewstudent", [
                \App\Controllers\Teacher\StudentController::class,
                "storenewStudentData",
            ])
            ->setName("teacher.student.addnewstudent.submit");

        $group
            ->get("/student/edit/{studentid}", [
                \App\Controllers\Teacher\StudentController::class,
                "indexEditStudent",
            ])
            ->setName("teacher.student.student_edit.index");

        $group
            ->post("/student/edit/{studentid}/update", [
                \App\Controllers\Teacher\StudentController::class,
                "UpdateStudent",
            ])
            ->setName("teacher.student.student_edit.index.submit");

        $group
            ->get("/student/edit/{studentid}/delete", [
                \App\Controllers\Teacher\StudentController::class,
                "DeleteStudent",
            ])
            ->setName("teacher.student.delete");

        // import student data from excel sheet
        $group
            ->get("/student/importfromexcel", [
                ImportExcelstudentController::class,
                "index",
            ])
            ->setName("teacher.student.importfromexcel.index");

        $group
            ->post("/student/importfromexcel", [
                ImportExcelstudentController::class,
                "store",
            ])
            ->setName("teacher.student.importfromexcel.submit");
        // import student data from excel sheet ends here

        // generate dummy excel sheet
        $group
            ->get(
                "/student/generate-excel-template",
                GenerateExcelTemplateController::class,
            )
            ->setName("teacher.student.generate-excel-template");
        // ======== student routes ends here =========================

        //============ attendance routes starts here
        // show attendance data for specific course login start here
        $group
            ->get("/attendance", [Attendancecontroller::class, "index"])
            ->setName("teacher.attendance.index");
        $group
            ->post("/attendance", [
                Attendancecontroller::class,
                "showallattendance",
            ])
            ->setName("teacher.attendance.submit");
        // show attendance data for specific course login ends here

        $group
            ->get("/attendance/show_student_to_mark_attendance", [
                Attendancecontroller::class,
                "showstudentdata_forspecific_course_for_marking_attendance",
            ])
            ->setName("teacher.attendance.showstudent_tomark_attendance");

        $group
            ->post("/attendance/mark_student_attendance", [
                Attendancecontroller::class,
                "store_markedattendance",
            ])
            ->setName("teacher.attendance.mark_student_attendance");

        $group
            ->get("/attendance/display_singlestudent_attendanceinformation", [
                Attendancecontroller::class,
                "display_single_student_attendance_information",
            ])
            ->setName(
                "teacher.attendance.display_singlestudent_attendanceinformation",
            );

        $group
            ->get("/attendance/update_singlestudent_attendanceinformation", [
                Attendancecontroller::class,
                "update_singlestudent_attendancedata",
            ])
            ->setName(
                "teacher.attendance.update_singlestudent_attendanceinformation",
            );

        $group
            ->get("/attendance/delete_singlestudent_attendanceinformation", [
                Attendancecontroller::class,
                "delete_singlestudent_attendance_information",
            ])
            ->setName(
                "teacher.attendance.delete_singlestudent_attendanceinformation",
            );

        $group
            ->get(
                "/attendance/deleteall_attendance_forsingle_student/{attendanceid}",
                [
                    Attendancecontroller::class,
                    "deleteall_attendance_for_single_student",
                ],
            )
            ->setName(
                "teacher.attendance.deleteall_attendance_forsingle_student",
            );

        // export attendance data for all students (without ajax)
        $group
            ->get(
                "/attendance/export_allAttendancedata/{courseid}",
                Exportall_attendancedata::class,
            )
            ->setName("teacher.attendance.export_allAttendancedata");
        // export single student attendance data (using ajax)
        $group
            ->get(
                "/attendance/export_singlestudent_Attendancedata",
                Export_singlestudent_attendancereport::class,
            )
            ->setName("teacher.attendance.export_singlestudent_Attendancedata");
        //============ attendance routes end here ==================================

        // ==== Assignment Routes Starts here
        $group
            ->get("/assignment", [Assignmentcontroller::class, "index"])
            ->setName("teacher.assignmnt.index");

        $group
            ->get("/assignment/createassignment", [
                Assignmentcontroller::class,
                "create",
            ])
            ->setName("teacher.assignmnt.create");

        $group
            ->post("/assignment/createassignment", [
                Assignmentcontroller::class,
                "store",
            ])
            ->setName("teacher.assignmnt.createassignment.submit");

        // display single assignmentform (ajax starts here)
        $group
            ->get("/assignment/displayassignmentdetails", [
                Assignmentcontroller::class,
                "displayassignmentdetails",
            ])
            ->setName("teacher.assignmnt.displayassignmentdetails");
        // # ajax ends here

        $group
            ->get("/assignment/{assignmentid}/edit", [
                Assignmentcontroller::class,
                "editassignmentform",
            ])
            ->setName("teacher.assignmnt.edit.index");

        $group
            ->post("/assignment/edit/{assignmentid}/update", [
                Assignmentcontroller::class,
                "updateassignmentform",
            ])
            ->setName("teacher.assignmnt.edit.submit");

        $group
            ->get("/assignment/edit/{assignmentid}/delete", [
                Assignmentcontroller::class,
                "deleteassignmentform",
            ])
            ->setName("teacher.assignmnt.delete");
        // ==== Assignment Routes Ends here
    })
        ->add(RemembermeMiddleware::class)
        ->add(AuthMiddleware::class);
};
