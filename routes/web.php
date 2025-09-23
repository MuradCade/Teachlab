<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middlewares\MaintainanceMode;
use App\Config\Logger;
use App\Controllers\Services\PHPMAILService;
use Slim\App;
use App\Controllers\Services\Redirector;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\RemembermeMiddleware;


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
    })
        ->add(RemembermeMiddleware::class)
        ->add(AuthMiddleware::class);
};
