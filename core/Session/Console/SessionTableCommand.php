<?php

namespace AwesomeCoder\Session\Console;

use AwesomeCoder\Console\Command;
use AwesomeCoder\Filesystem\Filesystem;
use AwesomeCoder\Support\Composer;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'session:table')]
class SessionTableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'session:table';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'session:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the session database table';

    /**
     * The filesystem instance.
     *
     * @var \AwesomeCoder\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \AwesomeCoder\Support\Composer
     */
    protected $composer;

    /**
     * Create a new session table command instance.
     *
     * @param  \AwesomeCoder\Filesystem\Filesystem  $files
     * @param  \AwesomeCoder\Support\Composer  $composer
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $fullPath = $this->createBaseMigration();

        $this->files->put($fullPath, $this->files->get(__DIR__ . '/stubs/database.stub'));

        $this->components->info('Migration created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the session.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = 'create_sessions_table';

        $path = $this->plugin->databasePath() . '/migrations';

        return $this->plugin['migration.creator']->create($name, $path);
    }
}
