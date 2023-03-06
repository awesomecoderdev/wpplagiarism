<?php

namespace AwesomeCoder\Validation;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Contracts\Validation\UncompromisedVerifier;
use AwesomeCoder\Http\Client\Factory as HttpFactory;
use AwesomeCoder\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenceVerifier();
        $this->registerUncompromisedVerifier();
        $this->registerValidationFactory();
    }

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator', function ($plugin) {
            $validator = new Factory($plugin['translator'], $plugin);

            // The validation presence verifier is responsible for determining the existence of
            // values in a given data collection which is typically a relational database or
            // other persistent data stores. It is used to check for "uniqueness" as well.
            if (isset($plugin['db'], $plugin['validation.presence'])) {
                $validator->setPresenceVerifier($plugin['validation.presence']);
            }

            return $validator;
        });
    }

    /**
     * Register the database presence verifier.
     *
     * @return void
     */
    protected function registerPresenceVerifier()
    {
        $this->app->singleton('validation.presence', function ($plugin) {
            return new DatabasePresenceVerifier($plugin['db']);
        });
    }

    /**
     * Register the uncompromised password verifier.
     *
     * @return void
     */
    protected function registerUncompromisedVerifier()
    {
        $this->app->singleton(UncompromisedVerifier::class, function ($plugin) {
            return new NotPwnedVerifier($plugin[HttpFactory::class]);
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
            'validator', 'validation.presence',
        ];
    }
}
