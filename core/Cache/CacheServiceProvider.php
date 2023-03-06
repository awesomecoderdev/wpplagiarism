<?php

namespace AwesomeCoder\Cache;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class CacheServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cache', function ($plugin) {
            return new CacheManager($plugin);
        });

        $this->app->singleton('cache.store', function ($plugin) {
            return $plugin['cache']->driver();
        });

        $this->app->singleton('cache.psr6', function ($plugin) {
            return new Psr16Adapter($plugin['cache.store']);
        });

        $this->app->singleton('memcached.connector', function () {
            return new MemcachedConnector;
        });

        $this->app->singleton(RateLimiter::class, function ($plugin) {
            return new RateLimiter($plugin->make('cache')->driver(
                $plugin['config']->get('cache.limiter')
            ));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'cache', 'cache.store', 'cache.psr6', 'memcached.connector', RateLimiter::class,
        ];
    }
}
