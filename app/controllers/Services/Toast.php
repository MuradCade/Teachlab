<?php

namespace App\Controllers\Services;

class Toast
{

    /*
    - warning toast Purpose: Alert the user about something that could become a problem.

    Examples:
      “You have 3 login attempts remaining.”
      “Your password will expire soon.”
    */
    public function warningToast(string $feedback): string
    {
        return "<p class='bg-warning-lights p-2 mt-2 mb-2 w-100'>{$feedback}</p>";
    }

    /*
    - success toast Purpose: Positive confirmation that an action completed successfully.
    
    Examples in login page:
        “Login successful. Redirecting…”
        “Password reset email sent successfully.”
    */
    public function successToast(string $feedback): string
    {
        return "<p class='bg-success-light p-2 mt-2 mb-2 w-100'>{$feedback}</p>";
    }

    /*
    - danger toast Purpose: Critical error or failure.

    Examples:
        “Incorrect username or password.”
        “Account temporarily locked due to multiple failed login attempts.”
        “Your account has been disabled.”
    */

    public function dangerToast(string $feedback): string
    {
        return "<p class='bg-danger-light p-2 mt-2 mb-2 w-100'>{$feedback}</p>";
    }

    /*
    - info toast Purpose: Neutral informational message.
    
    Examples:
        “Your session will expire in 10 minutes.”
        “Please verify your email to continue.”
    */
    public function infoToast(string $feedback): string
    {
        return "<p class='bg-info-light p-2 mt-2 mb-2 w-100'>{$feedback}</p>";
    }
}
