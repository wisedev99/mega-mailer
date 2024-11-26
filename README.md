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
- [Usage](#usage)
- [Example](#example)
- [Error Handling](#error-handling)
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
$mailer->to('recipient@example.com')
       ->subject('Test Email')
       ->message('This is a test email.')
       ->attach('/path/to/attachment.pdf') // (optional) Attach a file
       ->send();
```
# Example
with Markdown, Plain Text, and Attachment
Here’s a detailed example demonstrating how to send an email to a specific recipient using both a Markdown template and a plain text fallback, accompanied by an attachment:

```php
$mailer = new Mailer();
$mailer->to('recipient@example.com', 'Jone Jonse')
    ->subject('Test Email')
    ->markdown('mail.for_employee', [
        'name' => 'Jone',
        'message' => 'This is a test message with Blade!'
    ])
    ->message('This is a plain text message') // Fallback plain text -- repalce
    ->attach('/path/to/attachment.pdf') // Attach an attachment
    ->send();
```



# Error Handling

To handle errors during the sending process, you can check the result as follows:

# Contributing
Contributions are welcome! If you find an issue or have a feature request, please open an issue on the GitHub repository. Pull requests are also welcome — feel free to contribute code improvements or additional features.

# License
This project is licensed under the MIT License - see the LICENSE file for details.

Summary of Sections Included:
Basic Setup: A straightforward example showing how to use the mailer with basic parameters.
Example with Markdown, Plain Text, and Attachment: Detailed example that includes everything you’re using (Markdown, fallback text, and file attachment).
Markdown Emails: A concise example showing the use of Blade views for Markdown emails.
Configuration and Error Handling: Guidance on configuring SMTP settings and managing errors.
This comprehensive README is a great resource for users who want to quickly understand how to use your Mega Mailer package for PHP.
