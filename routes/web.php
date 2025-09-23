<?php

// use App\controller\Homecontroller;
use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Views\Twig;
use App\Middlewares\MaintainanceMode;
use App\Config\Logger;
use App\Controllers\Services\PHPMAILService;
use Slim\App;



return function (App $app) {

    // adding undermaintainance middleware globally
    $app->add(MaintainanceMode::class);


    $app->get('/', [\App\Controllers\PageController::class, 'indexHome'])
        ->setName('pages.home')->setName('page.index');

    $app->get('/create_account', [\App\Controllers\PageController::class, 'indexCreateAccount'])
        ->setName('pages.createaccount');

    $app->get('/login', [\App\Controllers\PageController::class, 'indexLogin'])
        ->setName('pages.login');

    $app->post('/create_account', [\App\Controllers\PageController::class, 'handleAccountCreation'])
        ->setName('page.hanldeaccountcreation');
    $app->get('/congratulations', [\App\Controllers\PageController::class, 'indexcongratspage'])
        ->setName('page.hanldeaccountcreation');


    // test mailer
    // $app->get('/test-mailer', [\App\Controllers\MailSender::class, 'sendTestEmail']);
    $app->get('/send-mailinqueue', function ($request, $response) use ($app) {
        $container = $app->getContainer();
        $logger = new Logger();
        $email = new PHPMAILService($container, $logger);

        $results = $email->sendmail();

        $response->getBody()->write(json_encode([
            'status' => 'email job processed',
            // 'results' => $results
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
