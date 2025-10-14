<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use SlimSession\Helper as Session;
use Slim\Psr7\Factory\ResponseFactory;

class AuthMiddleware
{
    protected  Session $session;
    // ResponseFactory is a factory class that helps generate HTTP response objects in a PSR-7 compliant way. It's especially useful when you're building middleware
    protected ResponseFactory $responsefactory;

    public function __construct(Session $session, ResponseFactory $responsefactory)
    {
        $this->session = $session;
        $this->responsefactory = $responsefactory;
    }
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        $userid = $this->session->get('userid');
        $rememberme = $_COOKIE['rememberme_token'] ?? null;
        // If no active session and no valid remember-me cookie → redirect to login
        if (!$userid && !$rememberme) {
            return $this->responsefactory->createResponse(302)
                ->withHeader('Location', '/login')
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->withHeader('Pragma', 'no-cache')
                ->withHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        }

        // Handle the request for authenticated users
        $response = $handler->handle($request);

        // Add headers to prevent browser caching on all protected pages
        return $response
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
