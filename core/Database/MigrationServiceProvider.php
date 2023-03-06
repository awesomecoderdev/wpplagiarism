<?php

namespace AwesomeCoder\Database;

use AwesomeCoder\Contracts\Events\Dispatcher;
use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Database\Console\Migrations\FreshCommand;
use AwesomeCoder\Database\Console\Migrations\InstallCommand;
use AwesomeCoder\Database\Console\Migrations\MigrateCommand;
use AwesomeCoder\Database\Console\Migrations\MigrateMakeCommand;
use AwesomeCoder\Database\Console\Migrations\RefreshCommand;
use AwesomeCoder\Database\Console\Migrations\ResetCommand;
use AwesomeCoder\Database\Console\Migrations\RollbackCommand;
use AwesomeCoder\Database\Console\Migrations\StatusCommand;
use AwesomeCoder\Database\Migrations\DatabaseMigrationRepository;
use AwesomeCoder\Database\Migrations\MigrationCreator;
use AwesomeCoder\Database\Migrations\Migrator;
use AwesomeCoder\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migrate' => MigrateCommand::class,
        'MigrateFresh' => FreshCommand::class,
        'MigrateInstall' => InstallCommand::class,
        'MigrateRefresh' => RefreshCommand::class,
        'MigrateReset' => ResetCommand::class,
        'MigrateRollback' => RollbackCommand::class,
        'MigrateStatus' => StatusCommand::class,
        'MigrateMake' => MigrateMakeCommand::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();

        $this->registerMigrator();

        $this->registerCreator();

        $this->registerCommands($this->commands);
    }

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('migration.repository', function ($plugin) {
            $table = $plugin['config']['database.migrations'];

            return new DatabaseMigrationRepository($plugin['db'], $table);
        });
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function ($plugin) {
            $repository = $plugin['migration.repository'];

            return new Migrator($repository, $plugin['db'], $plugin['files'], $plugin['events']);
        });
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function ($plugin) {
            return new MigrationCreator($plugin['files'], $plugin->basePath('stubs'));
        });
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton(MigrateCommand::class, function ($plugin) {
            return new MigrateCommand($plugin['migrator'], $plugin[Dispatcher::class]);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateFreshCommand()
    {
        $this->app->singleton(FreshCommand::class);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateInstallCommand()
    {
        $this->app->singleton(InstallCommand::class, function ($plugin) {
            return new InstallCommand($plugin['migration.repository']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton(MigrateMakeCommand::class, function ($plugin) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $plugin['migration.creator'];

            $composer = $plugin['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton(RefreshCommand::class);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton(ResetCommand::class, function ($plugin) {
            return new ResetCommand($plugin['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton(RollbackCommand::class, function ($plugin) {
            return new RollbackCommand($plugin['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton(StatusCommand::class, function ($plugin) {
            return new StatusCommand($plugin['migrator']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge([
            'migrator', 'migration.repository', 'migration.creator',
        ], array_values($this->commands));
    }
}
