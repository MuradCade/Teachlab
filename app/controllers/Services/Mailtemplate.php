<?php

namespace App\Controllers\Services;


class Mailtemplate
{

    public static function accountVerificationTemplate(string $fullname, string $verification_code, string $siteurl): string
    {
        return
            "
        <p>Asalamu Alaikum Mr/Mrs. {$fullname},</p>
        <p>Thanks for registering with TeachLab! Please confirm your email by clicking the link below:</p>
        <p><a href='{$siteurl}/emal_confirmation?email_token={$verification_code}'>Confirm Email</a></p>
        <p>If you didn’t create an account, you can safely ignore this email.</p>
        <p>Best Regards, Teachlab Team.</p>
        ";
    }
}
