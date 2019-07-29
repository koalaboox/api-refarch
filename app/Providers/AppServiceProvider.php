<?php

namespace App\Providers;

use App\Koalaboox\AuthenticateRequest;
use App\Koalaboox\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('koalaboox-api', function (Application $app) {
            $config = $app['config']->get('koalaboox');

            $handler = HandlerStack::create();
            $handler->push(new AuthenticateRequest(), 'auth');

            $client = new \GuzzleHttp\Client([
                'handler' => $handler,
                'base_uri' => $config['url'],
                'curl' => $config['curl'],
            ]);

            return new Client($client, $config['url'], $config['app']['id'], $config['app']['secret']);
        });

        $this->app->bind(Client::class, 'koalaboox-api');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
