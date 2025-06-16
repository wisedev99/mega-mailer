# Mega Mailer

**Version:** 1.0.0
**License:** MIT

Mega Mailer is a simple and elegant mailer package for PHP, designed to facilitate sending emails through SMTP with ease. Whether you need to send simple text emails, HTML content, or even Markdown formatted messages, Mega Mailer has you covered.

## Features

- **SMTP Support:** Easily send emails using SMTP.
- **Simple API:** Set recipients, subjects, and email bodies with a straightforward interface.
- **Support for Attachments:** Add attachments to your emails effortlessly.
- **Markdown Format Support:** Send Markdown formatted messages via Blade views.
- **Easy Error Handling:** Handle errors gracefully while sending emails.

## Table of Contents

- [Installation](#installation)
- [Creating a Mailable](#creating-a-mailable)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Installation

You can install Mega Mailer via Composer. Run the following command in your project directory:

```bash
composer require mega-tj/mailer:dev-main --dev
```

## Laravel Integration

### 1. Register the Service Provider

If your package is installed via Composer, Laravel will auto-discover your service provider if you add it to your `composer.json`:

```json
"extra": {
    "laravel": {
        "providers": [
            "Mega\\App\\Providers\\MailerServiceProvider"
        ]
    }
}
```

If you are testing manually or auto-discovery is not enabled, register the provider in your `config/app.php`:

```php
'providers' => [
    // ...
    Mega\App\Providers\MailerServiceProvider::class,
],
```

### 2. Publish the Configuration File

After installing the package, publish the configuration file with:

```bash
php artisan vendor:publish --tag=config
```

This will copy the config file from:

```
src/config/mailer.php  →  config/mailer.php
```

You can now access configuration values using:

```php
config('mailer.host'); // instead of env('MAIL_HOST')
```

# Creating a Mailable
To create your own Mailable, inherit from the Mailable base class provided by the package. Below is an example of how to structure a Test class:

# Example Test Mailable

```bash
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class Test extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Checklist Message',
            from: new Address('jeffrey@example.com', 'Jeffrey Way'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.send_report',
            with: [
                'data' => ['key' => 'value'],
                'content' => ['text' => 'Some content here']
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Return attachments if necessary
    }
}
```
# Usage
Basic Setup
Here’s a simple example to get started with Mega Mailer:
```bash
require 'vendor/autoload.php'; // Load the Composer autoload file

use Mega\App\Mailer;

// Create a new Mailer instance
$mailer = new Mailer();

// Set the email details
$recipient = ['test.userov@example.tj'];

$mailer->to($recipient)->send(new Test());
```
# Example of Using Mega Mailer in Laravel
To use Mega Mailer in a Laravel route:
```bash
use Mega\App\Mailer;
use App\Mail\Test;

Route::get('/test', function () {
    $recipient = ['aliakbar-boistov@example.tj'];

    $mailer = new Mailer();
    $mailer->to($recipient)->send(new Test());

    return 'Email sent!';
});
```


# Contributing
If you would like to contribute to this package, please open an issue or submit a pull request on the GitHub repository.

# License
This package is open-sourced software licensed under the MIT License.
