<?php

namespace App\Middlewares;

use App\Controllers\Services\Redirector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use SlimSession\Helper as Session;

class GuestMiddleware
{
    protected  Session $session;
    // ResponseFactory is a factory class that helps generate HTTP response objects in a PSR-7 compliant way. It's especially useful when you're building middleware

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {

        if (!empty($this->session->get('userid')) || isset($_COOKIE['rememberme_token'])) {
            return Redirector::redirect_to('/dashboard');
        }
        return $handler->handle($request);
    }
}
