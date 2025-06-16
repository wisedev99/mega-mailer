<?php

namespace Mega\App\Providers;

use Illuminate\Support\ServiceProvider;

class MailerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register config so `config('mailer.*')` works
        $this->mergeConfigFrom(__DIR__ . '/src/config/mailer.php', 'mailer');
    }

    public function boot()
    {
        // Allow publishing config to the main Laravel app
        $this->publishes([
            __DIR__ . '/src/config/mailer.php' => config_path('mailer.php'),
        ], 'config');
    }
}
