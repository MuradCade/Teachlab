<?php

use Slim\App;
use App\Config\Logger;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\MaintainanceMode;
use App\Controllers\Services\Redirector;
use App\Middlewares\RolebasedMiddleware;
use App\Middlewares\RemembermeMiddleware;
use App\Controllers\Services\PHPMAILService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return function (App $app) {

    // adding undermaintainance middleware globally
    $app->add(MaintainanceMode::class);



    // Home page
    $app->get('/', [\App\Controllers\PageController::class, 'indexHome'])
        ->setName('home.index');

    // Authentication
    $app->get('/create_account', [\App\Controllers\PageController::class, 'indexCreateAccount'])
        ->setName('auth.register')->add(GuestMiddleware::class);

    $app->post('/create_account', [\App\Controllers\PageController::class, 'handleAccountCreation'])
        ->setName('auth.register.submit')->add(GuestMiddleware::class);

    $app->get('/login', [\App\Controllers\PageController::class, 'indexLogin'])
        ->setName('auth.login')->add(GuestMiddleware::class);

    $app->post('/login', [\App\Controllers\PageController::class, 'handleLogin'])
        ->setName('auth.login.submit')->add(GuestMiddleware::class);

    // After account creation success page
    $app->get('/congratulations', [\App\Controllers\PageController::class, 'indexCongratsPage'])
        ->setName('auth.register.success');

    // Email verification
    $app->get('/email_confirmation', [\App\Controllers\PageController::class, 'verifyEmail'])
        ->setName('auth.verify');

    // Mail queue processor
    $app->get('/send-mailinqueue', function ($request, $response) use ($app) {
        $container = $app->getContainer();
        $logger = new Logger();
        $email = new PHPMAILService($container, $logger);

        $email->sendmail();

        $response->getBody()->write(json_encode([
            'status' => 'email job processed'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    })->setName('jobs.mail.process');


    /*
    this route : /dashboard
    is responsible to check user and redirect them to their proper dashboard
    */
    $app->get('/dashboard', [])->add(RolebasedMiddleware::class);

    // Teacher dashboard
    $app->group('/teacher', function (\Slim\Routing\RouteCollectorProxy $group) {

        // redirect /teacher → /teacher/dashboard
        $group->get('/', function () {
            return Redirector::redirect_to('/teacher/dashboard');
        })->setName('teacher.redirect');

        // teacher dashboard home
        $group->get('/dashboard', [\App\Controllers\Teacher\TeacherController::class, 'dashboardHome'])
            ->setName('teacher.dashboard.home');

        // teacher logout
        $group->get('/logout', [\App\Controllers\Teacher\TeacherController::class, 'logout'])
            ->setName('teacher.logout');


        // Course Routes Starts Here.

        $group->get('/course', [\App\Controllers\Teacher\CourseController::class, 'index'])
            ->setName('teacher.course.index');

        $group->get('/course/createcourse', [\App\Controllers\Teacher\CourseController::class, 'indexcreatecourse'])
            ->setName('teacher.course.createcourse.index');

        $group->post('/course/createcourse', [\App\Controllers\Teacher\CourseController::class, 'storeCourse'])
            ->setName('teacher.course.createcourse.submit');

        $group->get('/course/edit/{courseid}', [\App\Controllers\Teacher\CourseController::class, 'editCourse'])
            ->setName('teacher.course.course_edit.index');

        $group->post('/course/edit/{courseid}/update', [\App\Controllers\Teacher\CourseController::class, 'updateCourse'])
            ->setName('teacher.course.course_edit.update.submit');


        $group->get('/course/createcourse/edit/{courseid}/delete', [\App\Controllers\Teacher\CourseController::class, 'deleteCourse'])
            ->setName('teacher.course.course_edit.delete');

        // Course Routes Ends Here.


        // student routes starts here
        $group->get('/student', [\App\Controllers\Teacher\StudentController::class, 'index'])
            ->setName('teacher.student.index');

        $group->get('/student/addnewstudent', [\App\Controllers\Teacher\StudentController::class, 'indexAddnewstudent'])
            ->setName('teacher.student.addnewstudent.index');

        $group->post('/student/addnewstudent', [\App\Controllers\Teacher\StudentController::class, 'storenewStudentData'])
            ->setName('teacher.student.addnewstudent.submit');
        // student routes ends here


    })
        ->add(RemembermeMiddleware::class)
        ->add(AuthMiddleware::class);
};
