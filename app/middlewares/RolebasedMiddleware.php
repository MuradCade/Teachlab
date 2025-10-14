<?php

namespace App\Middlewares;

use App\Controllers\Services\RoleEnum;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use SlimSession\Helper as Session;
use Slim\Psr7\Factory\ResponseFactory;
use App\Config\Rememberme;

// rolebased middleware that helps to redirect user to thier proper dashboard
class RolebasedMiddleware
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

        // Role-based dashboard redirect (only when hitting /dashboard)
        $uri = $request->getUri()->getPath();
        $role = $this->session->get('userrole');

        // check if userrole not found , also check if cookie is found
        if (!$role && isset($_COOKIE['rememberme_token'])) {
            $rememberme = new Rememberme();
            $rememberme->authenticate(); // generate new cookie and set new session
        }

        if ($uri === '/dashboard') {
            switch ($role) {
                case RoleEnum::teacher:
                    return $this->responsefactory->createResponse(302)
                        ->withHeader('Location', '/teacher/dashboard');
                    // case 'student':
                    //     return $this->responsefactory->createResponse(302)
                    //         ->withHeader('Location', '/student/dashboard');
                case RoleEnum::admin:
                    return $this->responsefactory->createResponse(302)
                        ->withHeader('Location', '/admin/dashboard');
                default:
                    return $this->responsefactory->createResponse(302)
                        ->withHeader('Location', '/login');
            }
        }

        // Continue as normal for other requests
        $response = $handler->handle($request);

        return $response
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
