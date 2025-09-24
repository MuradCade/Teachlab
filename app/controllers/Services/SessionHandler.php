<?php

namespace App\Controllers\Services;

use SlimSession\Helper as Session;


class SessionHandler
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function setUserSession($user, $rolePrefix)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_regenerate_id(true);
        $this->session->set("{$rolePrefix}id", $user->userid);
        $this->session->set("{$rolePrefix}name", $user->fullname);
        $this->session->set("{$rolePrefix}email", $user->email);
        $this->session->set("{$rolePrefix}role", $user->role);
    }

    // public function addflash($flash, $message)
    // {
    //     if (session_status() !== PHP_SESSION_ACTIVE) {
    //         session_start();
    //     }
    //     $this->session->set($flash, $message);
    // }

    // public function flashmessages($flash): array|string
    // {
    //     if (session_status() !== PHP_SESSION_ACTIVE) {
    //         session_start();
    //     }

    //     if (!empty($this->session->get($flash))) {
    //         $feedback = $this->session->get($flash);
    //         $this->session->delete($flash) ?? [];
    //     }

    //     return $feedback;
    // }
}
