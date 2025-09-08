<?php

namespace App\Controllers\Services;

use Slim\Psr7\Response;


class Redirector
{
    public static function rememberLastUrl(string $url): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['last_url'] = $url;
    }

    public static function redirectToLastUrl(string $fallback = '/'): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $url = $_SESSION['last_url'] ?? '/';
        unset($_SESSION['last_url']);




        $response = new Response();
        return $response->withHeader('Location', $url)->withStatus(302);
    }
    public static function redirect_to(string $url): Response
    {
        // Ensure the URL starts with /
        if (strpos($url, '/') !== 0 && !preg_match('#^https?://#', $url)) {
            $url = '/' . ltrim($url, '/');
        }

        $response = new Response();
        return $response->withHeader('Location', $url)->withStatus(302);
    }
}

//usage 
// Save current URL manually
// Redirector::rememberLastUrl((string)$request->getUri());

// Later, redirect back after success action
// return Redirector::redirectToLastUrl('/default-dashboard');