<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use CustomPlesk\Client;

class PleskServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('plesk');
            $client = new Client($config['host']);
            $client->setSecretKey($config['secret_key']);
            return $client;
        });
    }
}
