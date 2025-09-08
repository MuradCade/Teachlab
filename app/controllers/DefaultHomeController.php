<?php

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\Controllers\Services\CsrfService;
use Psr\Container\ContainerInterface;
// use App\Controllers\Services\Redirector;

class DefaultHomeController
{
    protected Twig $view;
    protected CsrfService $csrf;
    protected ContainerInterface $container;


    public function __construct(CsrfService $csrf, Twig $view, ContainerInterface $container)
    {
        $this->csrf = $csrf;
        $this->view = $view;
        $this->container = $container;
    }

    // get routes
    public function indexHome(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $current_page = 'home';
        return $this->view->render($response, 'pages/index.twig', ['currentpage' => $current_page]);
    }
}
