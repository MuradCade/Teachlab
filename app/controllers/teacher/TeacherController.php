<?php

namespace App\Controllers\Teacher;

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

        $current_page = 'dashboard.home';
        return $this->view->render($response, 'teacher/dashboard.twig', ['session' => $this->session, 'current_page' => $current_page]);
    }

    //logout for instructor
    public function Logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $userid = $this->session->get('userid');


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
        // Regenerate session ID to avoid session fixation
        // session_regenerate_id(true);

        // Expire the SlimSession cookie
        $params = session_get_cookie_params();
        setcookie('teachlabs', '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);



        return Redirector::redirect_to("/login");
    }
}
