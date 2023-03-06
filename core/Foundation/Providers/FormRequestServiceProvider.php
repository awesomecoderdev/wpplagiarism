<?php

namespace AwesomeCoder\Foundation\Providers;

use AwesomeCoder\Contracts\Validation\ValidatesWhenResolved;
use AwesomeCoder\Foundation\Http\FormRequest;
use AwesomeCoder\Routing\Redirector;
use AwesomeCoder\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(FormRequest::class, function ($request, $plugin) {
            $request = FormRequest::createFrom($plugin['request'], $request);

            $request->setContainer($plugin)->setRedirector($plugin->make(Redirector::class));
        });
    }
}
