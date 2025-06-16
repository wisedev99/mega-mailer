<?php

namespace Mega\App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Config; // Add this

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
    private $attachments = [];
    private $isConfigured = false;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    /**
     * Configure PHPMailer using config() values — called lazily
     */
    private function configure()
    {
        if ($this->isConfigured) return;

        // Use Config facade instead of the config() function
        $this->mail->isSMTP();
        $this->mail->Host = Config::get('mailer.host');
        $this->mail->SMTPAuth = Config::get('mailer.smtp_auth', false);
        $this->mail->Username = Config::get('mailer.username');
        $this->mail->Password = Config::get('mailer.password');
        $this->mail->Port = Config::get('mailer.port', 25);
        $this->mail->SMTPSecure = Config::get('mailer.encryption') ? 'tls' : false;
        $this->mail->SMTPAutoTLS = Config::get('mailer.auto_tls', false);
        $this->mail->CharSet = Config::get('mailer.charset', 'UTF-8');
        $this->mail->SMTPOptions = Config::get('mailer.smtp_options', [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ]);

        $this->isConfigured = true;
    }


    public function to($email, $name = null)
    {
        $this->configure();

        if (is_array($email)) {
            foreach ($email as $addr) {
                $this->mail->addAddress($addr);
            }
        } else {
            $this->toEmail = $email;
            $this->toName = $name;
            $this->mail->addAddress($this->toEmail, $this->toName);
        }
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function message($body)
    {
        $this->isMarkdown = false;
        $this->body = $body;
        return $this;
    }

    public function from($mail_from_address = null, $mail_from_name = null)
    {
        $this->configure();

        $fromAddress = $mail_from_address ?? config('mailer.from_address');
        $fromName = $mail_from_name ?? config('mailer.from_name');

        $this->mail->setFrom($fromAddress, $fromName);
    }

    public function send($mailable = null)
    {
        try {
            $this->configure();

            if ($mailable) {
                $this->mail->Subject = $mailable->envelope()->subject;
                $this->from(
                    $mailable->envelope()->from->address ?? null,
                    $mailable->envelope()->from->name ?? null
                );
                $this->viewName = $mailable->content()->view;
                $this->data = $mailable->content()->with;
                $this->attachments = $mailable->attachments();
                $this->isMarkdown = true;
            }

            if ($this->isMarkdown) {
                if (view()->exists($this->viewName)) {
                    $this->mail->isHTML(true);
                    $this->mail->Body = view($this->viewName, $this->data)->render();
                } else {
                    $this->mail->isHTML(false);
                    $this->mail->Body = "Blade view not found. Here's a fallback message.";
                }
            } else {
                $this->mail->isHTML(false);
                $this->mail->Body = $this->body;
            }

            foreach ($this->attachments as $attachment) {
                if (file_exists($attachment)) {
                    $this->mail->addAttachment($attachment);
                }
            }

            $this->mail->send();

            return 'Message has been sent';
        } catch (Exception $e) {
            \Log::error('MegaMailer: Failed to send email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
