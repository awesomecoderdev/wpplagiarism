<?php

namespace AwesomeCoder\Auth\Passwords;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Support\ServiceProvider;

class PasswordResetServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPasswordBroker();
    }

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($plugin) {
            return new PasswordBrokerManager($plugin);
        });

        $this->app->bind('auth.password.broker', function ($plugin) {
            return $plugin->make('auth.password')->broker();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth.password', 'auth.password.broker'];
    }
}
