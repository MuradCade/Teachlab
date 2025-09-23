<?php

namespace App\Config;

use App\Models\Users;
use App\Controllers\Services\SessionHandler;
use SlimSession\Helper as Session;



class Rememberme
{
    private $usermodel;
    private SessionHandler $session;

    public function __construct()
    {
        $this->usermodel = new Users();
        $this->session = new SessionHandler(new Session);
    }

    /**
     * Generate and persist a new remember-me token
     */
    public function generateRemembermeToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32)); // 64-char token
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        // Save hashed token to DB
        $this->usermodel->where('userid', $userId)->update([
            'rememberme_token' => $hashedToken,
        ]);

        // Store plain token in cookie
        setcookie('rememberme_token', $token, [
            'expires'  => time() + (86400 * 30), // 30 days
            'path'     => '/',
            'secure'   => false,  // set false only in local dev
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        return $token;
    }

    /**
     * Attempt to authenticate via remember-me cookie
     */
    public function authenticate(): ?Users
    {
        if (!isset($_COOKIE['rememberme_token'])) {
            return null;
        }

        $token = $_COOKIE['rememberme_token'];

        // Find user by remember_token hash
        $user = $this->usermodel->whereNotNull('rememberme_token')->first();
        if ($user && password_verify($token, $user['rememberme_token'])) {
            // Rotate token (prevent replay attacks)
            $this->generateRemembermeToken($user->userid);

            // Establish session

            $this->session->setUserSession($user, 'user');


            return $user;
        }

        return null;
    }

    /**
     * Clear remember-me on logout
     */
    public function forget(int $userid): void
    {

        $this->usermodel->where('userid', $userid)->update([
            'rememberme_token' => null,
        ]);

        // Delete the cookie in browser
        if (isset($_COOKIE['rememberme_token'])) {
            setcookie('rememberme_token', '', [
                'expires'  => time() - 3600, // set to past
                'path'     => '/',            // must match original path
                'secure'   => false,          // set to true if cookie was set with secure
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            unset($_COOKIE['rememberme_token']); // remove from PHP global array
        }
    }
}
