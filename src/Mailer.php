<?php

namespace Mega\App;

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
    private $attachments = [];  // Add an array to hold attachments

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
        $this->mail->CharSet = 'UTF-8';
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
        if (is_array($email)) {
            foreach ($email as $addr) {
                // $this->to($addr);
                $this->mail->addAddress($addr); // Add recipient
            }
        } else {
            $this->toEmail = $email;
            $this->toName = $name;
            $this->mail->addAddress($this->toEmail, $this->toName); // Add recipient
        }
        return $this;
    }

    // Set the subject of the email
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    // Set the plain message body
    public function message($body)
    {
        $this->isMarkdown = false;
        $this->body = $body;
        return $this;
    }

    public function from($mail_from_address = null, $mail_from_name = null)
    {
        if ($mail_from_address) {
            $this->mail->setFrom($mail_from_address, $mail_from_name);
        } else {
            $this->mail->setFrom(env('MAIL_FROM_ADDRESS', ''), env('MAIL_FROM_NAME', 'APP_NAME'));
        }
    }

    // Send the email
    public function send($mailable = null)
    {
        try {

            if ($mailable) {
                $this->mail->Subject = $mailable->envelope()->subject;
                $this->from($mailable->envelope()->from->address ?? null, $mailable->envelope()->from->name ?? null);
                $this->viewName = $mailable->content()->view;
                $this->data = $mailable->content()->with; // You can passing data if your Mailable class accepts some
                $this->attachments = $mailable->attachments();
                $this->isMarkdown = true;
            }

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

            // Attach files if any
            foreach ($this->attachments as $attachment) {
                if (file_exists($attachment)) {
                    $this->mail->addAttachment($attachment); // Add attachments
                }
            }

            // Send the email
            $this->mail->send();
            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
