<?php

use Slim\App;
use App\Config\Logger;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\MaintainanceMode;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\Services\Redirector;
use App\Middlewares\RolebasedMiddleware;
use App\Middlewares\RemembermeMiddleware;
use App\Controllers\Services\PHPMAILService;
use App\Controllers\Teacher\Assignmentcontroller;
use App\Controllers\Teacher\Attendancecontroller;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\Teacher\Exportall_attendancedata;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Teacher\ImportExcelstudentController;
use App\Controllers\Teacher\GenerateExcelTemplateController;
use App\Controllers\Teacher\Export_singlestudent_attendancereport;

return function (App $app) {
    // adding undermaintainance middleware globally
    $app->add(MaintainanceMode::class);

    // Home page
    $app->get("/", [
        \App\Controllers\PageController::class,
        "indexHome",
    ])->setName("home.index");

    // Authentication
    $app->get("/create_account", [
        \App\Controllers\PageController::class,
        "indexCreateAccount",
    ])
        ->setName("auth.register")
        ->add(GuestMiddleware::class);

    $app->post("/create_account", [
        \App\Controllers\PageController::class,
        "handleAccountCreation",
    ])
        ->setName("auth.register.submit")
        ->add(GuestMiddleware::class);

    $app->get("/login", [\App\Controllers\PageController::class, "indexLogin"])
        ->setName("auth.login")
        ->add(GuestMiddleware::class);

    $app->post("/login", [
        \App\Controllers\PageController::class,
        "handleLogin",
    ])
        ->setName("auth.login.submit")
        ->add(GuestMiddleware::class);

    // After account creation success page
    $app->get("/congratulations", [
        \App\Controllers\PageController::class,
        "indexCongratsPage",
    ])->setName("auth.register.success");

    // Email verification
    $app->get("/email_confirmation", [
        \App\Controllers\PageController::class,
        "verifyEmail",
    ])->setName("auth.verify");

    // Mail queue processor
    $app->get("/send-mailinqueue", function ($request, $response) use ($app) {
        $container = $app->getContainer();
        $logger = new Logger();
        $email = new PHPMAILService($container, $logger);

        $email->sendmail();

        $response->getBody()->write(
            json_encode([
                "status" => "email job processed",
            ]),
        );
        return $response->withHeader("Content-Type", "application/json");
    })->setName("jobs.mail.process");

    /*
    this route : /dashboard
    is responsible to check user and redirect them to their proper dashboard
    */
    $app->get("/dashboard", [])->add(RolebasedMiddleware::class);

    // require __DIR__ . '\teacher.php';
}; #}
