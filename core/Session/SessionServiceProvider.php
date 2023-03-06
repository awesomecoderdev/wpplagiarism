<?php

namespace AwesomeCoder\Session;

use AwesomeCoder\Contracts\Cache\Factory as CacheFactory;
use AwesomeCoder\Session\Middleware\StartSession;
use AwesomeCoder\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton(StartSession::class, function ($plugin) {
            return new StartSession($plugin->make(SessionManager::class), function () use ($plugin) {
                return $plugin->make(CacheFactory::class);
            });
        });
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->app->singleton('session', function ($plugin) {
            return new SessionManager($plugin);
        });
    }

    /**
     * Register the session driver instance.
     *
     * @return void
     */
    protected function registerSessionDriver()
    {
        $this->app->singleton('session.store', function ($plugin) {
            // First, we will create the session manager which is responsible for the
            // creation of the various session drivers when they are needed by the
            // application instance, and will resolve them on a lazy load basis.
            return $plugin->make('session')->driver();
        });
    }
}
