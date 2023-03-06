<?php

namespace AwesomeCoder\Queue\Console;

use AwesomeCoder\Console\Command;
use AwesomeCoder\Queue\Failed\PrunableFailedJobProvider;
use AwesomeCoder\Support\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'queue:prune-failed')]
class PruneFailedJobsCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'queue:prune-failed
                {--hours=24 : The number of hours to retain failed jobs data}';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'queue:prune-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale entries from the failed jobs table';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $failer = $this->plugin['queue.failer'];

        if ($failer instanceof PrunableFailedJobProvider) {
            $count = $failer->prune(Carbon::now()->subHours($this->option('hours')));
        } else {
            $this->components->error('The [' . class_basename($failer) . '] failed job storage driver does not support pruning.');

            return 1;
        }

        $this->components->info("{$count} entries deleted.");
    }
}
