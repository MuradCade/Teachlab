<?php

// use App\controller\Homecontroller;
use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Views\Twig;

use Slim\App;



return function (App $app) {

    $app->get('/', [\App\Controllers\PageController::class, 'indexHome'])
        ->setName('pages.home')->setName('page.index');

    $app->get('/create_account', [\App\Controllers\PageController::class, 'indexCreateAccount'])
        ->setName('pages.createaccount');

    $app->get('/login', [\App\Controllers\PageController::class, 'indexLogin'])
        ->setName('pages.login');
};
