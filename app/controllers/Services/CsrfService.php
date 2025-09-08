<?php

namespace App\Controllers\Services;

class CsrfService
{
    protected string $tokenKey = 'csrf_token';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[$this->tokenKey])) {
            $this->generateToken();
        }
    }

    public function getToken(): string
    {
        return $_SESSION[$this->tokenKey];
    }

    public function generateToken(): string
    {
        $_SESSION[$this->tokenKey] = bin2hex(random_bytes(32));
        return $_SESSION[$this->tokenKey];
    }

    public function verify(?string $token): bool
    {
        return hash_equals($_SESSION[$this->tokenKey] ?? '', $token ?? '');
    }

    public function regenerateToken(): string
    {
        return $this->generateToken();
    }
}
