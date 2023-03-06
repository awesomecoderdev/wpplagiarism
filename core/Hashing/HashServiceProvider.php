<?php

namespace AwesomeCoder\Hashing;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Support\ServiceProvider;

class HashServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hash', function ($plugin) {
            return new HashManager($plugin);
        });

        $this->app->singleton('hash.driver', function ($plugin) {
            return $plugin['hash']->driver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash', 'hash.driver'];
    }
}
