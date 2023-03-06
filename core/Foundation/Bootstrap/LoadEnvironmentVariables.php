<?php

namespace AwesomeCoder\Foundation\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use AwesomeCoder\Contracts\Foundation\Application;
use AwesomeCoder\Support\Env;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class LoadEnvironmentVariables
{
    /**
     * Bootstrap the given application.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return void
     */
    public function bootstrap(Application $plugin)
    {
        if ($plugin->configurationIsCached()) {
            return;
        }

        $this->checkForSpecificEnvironmentFile($plugin);

        try {
            $this->createDotenv($plugin)->safeLoad();
        } catch (InvalidFileException $e) {
            $this->writeErrorAndDie($e);
        }
    }

    /**
     * Detect if a custom environment file matching the APP_ENV exists.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return void
     */
    protected function checkForSpecificEnvironmentFile($plugin)
    {
        if (
            $plugin->runningInConsole() &&
            ($input = new ArgvInput)->hasParameterOption('--env') &&
            $this->setEnvironmentFilePath($plugin, $plugin->environmentFile() . '.' . $input->getParameterOption('--env'))
        ) {
            return;
        }

        $environment = Env::get('APP_ENV');

        if (!$environment) {
            return;
        }

        $this->setEnvironmentFilePath(
            $plugin,
            $plugin->environmentFile() . '.' . $environment
        );
    }

    /**
     * Load a custom environment file.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @param  string  $file
     * @return bool
     */
    protected function setEnvironmentFilePath($plugin, $file)
    {
        if (is_file($plugin->environmentPath() . '/' . $file)) {
            $plugin->loadEnvironmentFrom($file);

            return true;
        }

        return false;
    }

    /**
     * Create a Dotenv instance.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return \Dotenv\Dotenv
     */
    protected function createDotenv($plugin)
    {
        return Dotenv::create(
            Env::getRepository(),
            $plugin->environmentPath(),
            $plugin->environmentFile()
        );
    }

    /**
     * Write the error information to the screen and exit.
     *
     * @param  \Dotenv\Exception\InvalidFileException  $e
     * @return void
     */
    protected function writeErrorAndDie(InvalidFileException $e)
    {
        $output = (new ConsoleOutput)->getErrorOutput();

        $output->writeln('The environment file is invalid!');
        $output->writeln($e->getMessage());

        http_response_code(500);

        exit(1);
    }
}
