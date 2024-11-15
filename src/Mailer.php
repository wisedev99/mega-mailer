<?php

namespace Megafon\App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;
    private $subject;
    private $toEmail;
    private $toName;
    private $body;
    private $isMarkdown = false;
    private $viewName = '';
    private $data = [];

    public function __construct()
    {
        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = env('MAIL_HOST', '');  // Example SMTP host
        $this->mail->SMTPAuth = false;      // Disable SMTP authentication
        $this->mail->Username = '';         // SMTP username
        $this->mail->Password = '';         // SMTP password
        $this->mail->Port = 25;             // SMTP port
        $this->mail->SMTPSecure = false;    // No encryption
        $this->mail->SMTPAutoTLS = false;
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
    }

    // Set the email recipient
    public function to($email, $name = null)
    {
        $this->toEmail = $email;
        $this->toName = $name;
        return $this;
    }

    // Set the subject of the email
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    // Enable sending with a Blade view (Markdown)
    public function markdown($viewName, $data = [])
    {
        $this->isMarkdown = true;
        $this->viewName = $viewName;
        $this->data = $data;
        return $this;
    }

    // Set the plain message body
    public function message($body)
    {
        $this->isMarkdown = false;
        $this->body = $body;
        return $this;
    }

    // Send the email
    public function send()
    {
        try {
            // Recipients
            $this->mail->setFrom(env('MAIL_FROM_ADDRESS', ''), env('MAIL_FROM_ADDRESS', 'APP_NAME'));
            $this->mail->addAddress($this->toEmail, $this->toName); // Add recipient

            // Subject
            $this->mail->Subject = $this->subject;

            // Decide whether to use Blade or plain text
            if ($this->isMarkdown) {
                if (view()->exists($this->viewName)) {
                    $this->mail->isHTML(true);
                    $body = view($this->viewName, $this->data)->render();
                    $this->mail->Body = $body;
                } else {
                    // If Blade view doesn't exist, send a plain message
                    $this->mail->isHTML(false);
                    $this->mail->Body = "Blade view not found. Here's a fallback message.";
                }
            } else {
                $this->mail->isHTML(false);
                $this->mail->Body = $this->body; // Plain text message
            }

            // Send the email
            $this->mail->send();
            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
