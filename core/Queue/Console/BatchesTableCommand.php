<?php

namespace AwesomeCoder\Queue\Console;

use AwesomeCoder\Console\Command;
use AwesomeCoder\Filesystem\Filesystem;
use AwesomeCoder\Support\Composer;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'queue:batches-table')]
class BatchesTableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:batches-table';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'queue:batches-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the batches database table';

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
     * Create a new batched queue jobs table command instance.
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
        $table = $this->plugin['config']['queue.batching.table'] ?? 'job_batches';

        $this->replaceMigration(
            $this->createBaseMigration($table),
            $table
        );

        $this->components->info('Migration created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the table.
     *
     * @param  string  $table
     * @return string
     */
    protected function createBaseMigration($table = 'job_batches')
    {
        return $this->plugin['migration.creator']->create(
            'create_' . $table . '_table',
            $this->plugin->databasePath() . '/migrations'
        );
    }

    /**
     * Replace the generated migration with the batches job table stub.
     *
     * @param  string  $path
     * @param  string  $table
     * @return void
     */
    protected function replaceMigration($path, $table)
    {
        $stub = str_replace(
            '{{table}}',
            $table,
            $this->files->get(__DIR__ . '/stubs/batches.stub')
        );

        $this->files->put($path, $stub);
    }
}
