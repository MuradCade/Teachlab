<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use SlimSession\Helper as Session;
// use Slim\Psr7\Factory\ResponseFactory;
use App\Config\Rememberme;

class RemembermeMiddleware
{
    protected  Session $session;
    // ResponseFactory is a factory class that helps generate HTTP response objects in a PSR-7 compliant way. It's especially useful when you're building middleware

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {

        if (!$this->session->get('userid')) {
            $rememberme = new Rememberme();
            $rememberme->authenticate();
        }
        return $handler->handle($request);
    }
}
