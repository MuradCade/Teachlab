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
        $this->session->set("{$rolePrefix}_id", $user->id);
        $this->session->set("{$rolePrefix}_username", $user->username);
        $this->session->set("{$rolePrefix}_email", $user->email);
        $this->session->set("{$rolePrefix}_role", $user->role);
    }
}
