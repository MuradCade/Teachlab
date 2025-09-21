<?php

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\Controllers\Services\CsrfService;
use Psr\Container\ContainerInterface;
use App\Controllers\Services\Toast;

class PageController
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

    // homepage
    public function indexHome(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $current_page = 'home';
        return $this->view->render($response, 'pages/index.twig', ['currentpage' => $current_page]);
    }
    // render create account page
    public function indexCreateAccount(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $toast = new Toast();


        return $this->view->render($response, 'pages/register.twig', ['toast' => $toast]);
    }
    // login page
    public function indexLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $current_page = 'login';
        return $this->view->render($response, 'pages/login.twig', ['currentpage' => $current_page]);
    }
}
