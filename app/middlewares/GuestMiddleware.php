<?php

namespace App\Middlewares;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use SlimSession\Helper as Session;
use Slim\Psr7\Factory\ResponseFactory;
use App\Controllers\Services\RoleEnum;

class GuestMiddleware
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


        if (!empty($this->session->get('userid')) && isset($_COOKIE['rememberme_token'])) {

            if ($this->session->get('userrole') == RoleEnum::teacher) {
                $response = $this->responsefactory->createResponse(302)
                    ->withHeader('Location', 'http://teachlabs.test/teacher/dashboard');
                return $response;
            }
        }




        return $handler->handle($request);
    }
}
