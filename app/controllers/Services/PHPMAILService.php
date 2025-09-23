<?php

namespace App\Controllers\Services;

// use Carbon\Carbon;
use App\Config\Logger;
use Psr\Container\ContainerInterface;
use App\Models\MailQueueu;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//Send Email From Mailqueue table Using PHPMAILER
class PHPMAILService
{
    protected  ContainerInterface $container;
    private Logger  $logger;

    public function __construct(ContainerInterface $container, Logger  $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }
    public function sendmail(): array
    {
        $config = $this->container->get('config');
        // $cutoff = Carbon::now()->subMinute();

        $emails = MailQueueu::where('mail_status', 'pending')
            ->where('mail_type', 'signup')
            ->get();
        // $emails = MailQueue::where('status', 'pending')
        // ->where('mail_type', 'activation')
        // ->where('updated_at', '<=', $cutoff)
        // ->get();

        $results = [];
        foreach ($emails as $email) {
            if ($email->mail_status === "pending") {

                $this->logger->info("email sending is began: {$email->useremail} and {$email->mail_status}");
                $email->mail_status = "processing";
                $email->save();

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $config['MAIL']['PHPMAILER']['EMAIL'];
                    $mail->Password = $config['MAIL']['PHPMAILER']['SECRET_KEY'];
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom($config['MAIL']['PHPMAILER']['EMAIL'], 'TeachLab');
                    $mail->addAddress($email->useremail, $email->username);
                    $mail->isHTML(true);
                    $mail->Subject = $email->subject;
                    $mail->Body = $email->body;

                    $mail->send();

                    $email->mail_status = "sent";
                    $email->mail_error  = null;

                    $results[] = [
                        'recipient' => $email->useremail,
                        'status' => 'sent'
                    ];
                    $this->logger->info("email is now sent: {$email->useremail}");
                } catch (Exception $e) {
                    $email->mail_status = 'error';
                    $email->mail_error  = $mail->ErrorInfo ?? $e->getMessage();

                    $results[] = [
                        'recipient' => $email->useremail,
                        'status' => 'error',
                        'error' => $email->mail_error
                    ];
                    $this->logger->error("email is failed: {$email->useremail}, reason: {$email->mail_error}");
                } finally {
                    $email->save(); // always save status, whether success or failure
                }

                $email->save();
            } else {
                $this->logger->warning("Attempt to resend already processed email: {$email->useremail}");
            }
        }





        return $results;
    }
}
