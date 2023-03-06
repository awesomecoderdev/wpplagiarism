<?php

namespace AwesomeCoder\Foundation\Providers;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Support\Composer;
use AwesomeCoder\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('composer', function ($plugin) {
            return new Composer($plugin['files'], $plugin->basePath());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['composer'];
    }
}
