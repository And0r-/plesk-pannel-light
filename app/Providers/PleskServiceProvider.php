<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use CustomPlesk\Client;
use RuntimeException;

class PleskServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('plesk');

            // Ensure that required environment variables are set
            if (empty($config['host'])) {
                throw new RuntimeException('PLESK_HOST is not set or empty. Please check your .env file.');
            }

            if (empty($config['secret_key'])) {
                throw new RuntimeException('PLESK_SECRET_KEY is not set or empty. Please check your .env file.');
            }

            // Initialize the Plesk API client
            $client = new Client($config['host']);
            $client->setSecretKey($config['secret_key']);

            return $client;
        });
    }
}
