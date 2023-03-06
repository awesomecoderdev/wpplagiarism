<?php

namespace AwesomeCoder\Auth;

use AwesomeCoder\Auth\Access\Gate;
use AwesomeCoder\Auth\Middleware\RequirePassword;
use AwesomeCoder\Contracts\Auth\Access\Gate as GateContract;
use AwesomeCoder\Contracts\Auth\Authenticatable as AuthenticatableContract;
use AwesomeCoder\Contracts\Routing\ResponseFactory;
use AwesomeCoder\Contracts\Routing\UrlGenerator;
use AwesomeCoder\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthenticator();
        $this->registerUserResolver();
        $this->registerAccessGate();
        $this->registerRequirePassword();
        $this->registerRequestRebindHandler();
        $this->registerEventRebindHandler();
    }

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->singleton('auth', fn ($plugin) => new AuthManager($plugin));

        $this->app->singleton('auth.driver', fn ($plugin) => $plugin['auth']->guard());
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerUserResolver()
    {
        $this->app->bind(AuthenticatableContract::class, fn ($plugin) => call_user_func($plugin['auth']->userResolver()));
    }

    /**
     * Register the access gate service.
     *
     * @return void
     */
    protected function registerAccessGate()
    {
        $this->app->singleton(GateContract::class, function ($plugin) {
            return new Gate($plugin, fn () => call_user_func($plugin['auth']->userResolver()));
        });
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerRequirePassword()
    {
        $this->app->bind(RequirePassword::class, function ($plugin) {
            return new RequirePassword(
                $plugin[ResponseFactory::class],
                $plugin[UrlGenerator::class],
                $plugin['config']->get('auth.password_timeout')
            );
        });
    }

    /**
     * Handle the re-binding of the request binding.
     *
     * @return void
     */
    protected function registerRequestRebindHandler()
    {
        $this->app->rebinding('request', function ($plugin, $request) {
            $request->setUserResolver(function ($guard = null) use ($plugin) {
                return call_user_func($plugin['auth']->userResolver(), $guard);
            });
        });
    }

    /**
     * Handle the re-binding of the event dispatcher binding.
     *
     * @return void
     */
    protected function registerEventRebindHandler()
    {
        $this->app->rebinding('events', function ($plugin, $dispatcher) {
            if (
                !$plugin->resolved('auth') ||
                $plugin['auth']->hasResolvedGuards() === false
            ) {
                return;
            }

            if (method_exists($guard = $plugin['auth']->guard(), 'setDispatcher')) {
                $guard->setDispatcher($dispatcher);
            }
        });
    }
}
