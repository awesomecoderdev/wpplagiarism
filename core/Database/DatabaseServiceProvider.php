<?php

namespace AwesomeCoder\Database;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use AwesomeCoder\Contracts\Queue\EntityResolver;
use AwesomeCoder\Database\Connectors\ConnectionFactory;
use AwesomeCoder\Database\Eloquent\Model;
use AwesomeCoder\Database\Eloquent\QueueEntityResolver;
use AwesomeCoder\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * The array of resolved Faker instances.
     *
     * @var array
     */
    protected static $fakers = [];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);

        Model::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Model::clearBootedModels();

        $this->registerConnectionServices();
        $this->registerEloquentFactory();
        $this->registerQueueableEntityResolver();
    }

    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($plugin) {
            return new ConnectionFactory($plugin);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($plugin) {
            return new DatabaseManager($plugin, $plugin['db.factory']);
        });

        $this->app->bind('db.connection', function ($plugin) {
            return $plugin['db']->connection();
        });

        $this->app->bind('db.schema', function ($plugin) {
            return $plugin['db']->connection()->getSchemaBuilder();
        });

        $this->app->singleton('db.transactions', function ($plugin) {
            return new DatabaseTransactionsManager;
        });
    }

    /**
     * Register the Eloquent factory instance in the container.
     *
     * @return void
     */
    protected function registerEloquentFactory()
    {
        $this->app->singleton(FakerGenerator::class, function ($plugin, $parameters) {
            $locale = $parameters['locale'] ?? $plugin['config']->get('app.faker_locale', 'en_US');

            if (!isset(static::$fakers[$locale])) {
                static::$fakers[$locale] = FakerFactory::create($locale);
            }

            static::$fakers[$locale]->unique(true);

            return static::$fakers[$locale];
        });
    }

    /**
     * Register the queueable entity resolver implementation.
     *
     * @return void
     */
    protected function registerQueueableEntityResolver()
    {
        $this->app->singleton(EntityResolver::class, function () {
            return new QueueEntityResolver;
        });
    }
}
