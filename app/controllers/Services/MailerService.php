<?php

namespace App\Controllers\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function send($to, $subject, $body)
    {
        $email = (new Email())
            ->from('sandbox@sandbox.mailtrap.io')
            ->to($to)
            ->subject($subject)
            ->text('Plain text message')
            ->html($body);

        $this->mailer->send($email);
    }
}
