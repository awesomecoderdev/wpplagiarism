<?php

namespace AwesomeCoder\Queue\Console;

use AwesomeCoder\Console\Command;
use AwesomeCoder\Console\ConfirmableTrait;
use AwesomeCoder\Contracts\Queue\ClearableQueue;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'queue:clear')]
class ClearCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:clear';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'queue:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all of the jobs from the specified queue';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $connection = $this->argument('connection')
            ?: $this->plugin['config']['queue.default'];

        // We need to get the right queue for the connection which is set in the queue
        // configuration file for the application. We will pull it based on the set
        // connection being run for the queue operation currently being executed.
        $queueName = $this->getQueue($connection);

        $queue = $this->plugin['queue']->connection($connection);

        if ($queue instanceof ClearableQueue) {
            $count = $queue->clear($queueName);

            $this->components->info('Cleared ' . $count . ' jobs from the [' . $queueName . '] queue');
        } else {
            $this->components->error('Clearing queues is not supported on [' . (new ReflectionClass($queue))->getShortName() . ']');
        }

        return 0;
    }

    /**
     * Get the queue name to clear.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getQueue($connection)
    {
        return $this->option('queue') ?: $this->plugin['config']->get(
            "queue.connections.{$connection}.queue",
            'default'
        );
    }

    /**
     *  Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['connection', InputArgument::OPTIONAL, 'The name of the queue connection to clear'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['queue', null, InputOption::VALUE_OPTIONAL, 'The name of the queue to clear'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
