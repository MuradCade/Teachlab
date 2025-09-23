<?php

namespace App\Controllers\Services;


class Mailtemplate
{

    public static function accountVerificationTemplate(string $fullname, string $verification_code, string $siteurl): string
    {
        return
            "
        <h4>Welcome To TeachLab</h4>
        <p>Asalamu Alaikum Mr/Mrs. <strong>{$fullname}</strong>.</p>
        <p>Thanks for registering with TeachLab Please confirm your email by clicking the link below:</p>
        <p><a href='{$siteurl}/email_confirmation?email_token={$verification_code}'>Confirm Email</a></p><br>
        <p>If you did not create an account, you can safely ignore this email.</p>
        <p>Best Regards, Teachlab Team.</p>
        ";
    }
}
