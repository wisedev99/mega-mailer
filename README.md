# Mega Mailer

### License: MIT
### Version: 1.0.0

Mega Mailer is a simple and elegant mailer package for PHP, designed to facilitate sending emails through SMTP with ease. Whether you need to send simple text emails, HTML content, or even Markdown formatted messages, Mega Mailer has you covered.

# Features
- SMTP support for sending emails
- Simple API for setting recipients, subjects, and bodies
- Support for attachments
- Markdown format support via Blade views
- Easy error handling

# Table of Contents
- [Installation](#installation)
- [Creating a Mailable](#creating-mailable)
- [Contributing](#contributing)
- [License](#license)

# Installation
You can install Mega Mailer via Composer. In your project directory, run the following command:
```bash
composer require mega-tj/mailer:dev-main --dev
```

# Usage
Basic Setup
Here’s a simple example to get started:
```php
require 'vendor/autoload.php'; // Load the Composer autoload file

use Mega\App\Mailer;

// Create a new Mailer instance
$mailer = new Mailer();

// Set the email details
$recipient = ['test.userov@example.tj'];  // Email address also can be strig

$this->mailer->to($recipient)->send(new Test());

```
# Creating a Mailable
To create your own Mailable, utilize the Mailable base class. Here's how the Test class is structured:

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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Checklist Message',
            from: new Address('jeffrey@example.com', 'Jeffrey Way'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.send_report',
            with: ['data' => [], 'content' => []],
        );
    }

    public function attachments(): array
    {
        return []; // Return an array of attachments if necessary
    }
}
```

# Contributing
If you would like to contribute to this package, please open an issue or submit a pull request.

# License
This package is open-sourced software licensed under the MIT license.
