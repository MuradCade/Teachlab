<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Services\MailerService;
use App\Models\MailQueueu;
use App\Config\Logger;

// this mailsender is used when sending email with mailtrap

class MailSender
{
    private MailerService $mailerService;
    private Logger  $logger;


    public function __construct(MailerService $mailerService, Logger  $logger)
    {
        $this->mailerService = $mailerService;
        $this->logger = $logger;
    }

    public function sendTestEmail(Request $request, Response $response): Response
    {
        $emails = MailQueueu::where('mail_status', 'pending')
            ->where('mail_type', 'signup')
            ->get();
        $results = [];
        foreach ($emails as $email) {
            if ($email->mail_status === "pending") {
                $this->logger->info("Processing email: {$email->useremail}");
                $email->mail_status = "processing";
                $email->save();

                try {
                    $this->mailerService->send($email->useremail, $email->subject, $email->body);
                    $email->mail_status = "sent";
                    $email->mail_error = null;
                    $this->logger->info("Email sent: {$email->useremail}");
                } catch (\Throwable $e) {
                    $email->mail_status = "error";
                    $email->mail_error = $e->getMessage();
                    $this->logger->error("Email failed: {$email->useremail}, reason: {$e->getMessage()}");
                } finally {
                    $email->save(); // ensures status is always updated
                }
            }
        }

        $response->getBody()->write('Email sent successfully via MailerService!');
        return $response;
    }
}
