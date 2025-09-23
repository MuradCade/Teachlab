<?php

namespace App\Controllers\teacher;

use Psr\Http\Message\ResponseInterface;

use Psr\Http\Message\ServerRequestInterface;
use SlimSession\Helper as Session;
use App\Controllers\Services\Redirector;
use App\Config\Rememberme;

use Slim\Views\Twig;

class TeacherController
{
    protected Twig $view;
    protected Session $session;
    public function __construct(Twig $view, Session $session)
    {
        $this->view = $view;
        $this->session = $session;
    }


    public function dashboardHome(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {


        return $this->view->render($response, 'teacher/dashboard.twig', ['session' => $this->session]);
    }

    //logout for instructor
    public function Logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $userid = $this->session->userid;

        if ($userid) {
            $rememberme = new Rememberme();
            $rememberme->forget($userid);
        }
        //clean session
        $this->session->delete('userid');
        $this->session->delete('username');
        $this->session->delete('useremail');
        $this->session->delete('userrole');
        $this->session->destroy();

        return Redirector::redirect_to("teacher/dashboard");
    }
}
