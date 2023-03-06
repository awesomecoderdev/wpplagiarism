<?php

namespace AwesomeCoder\Foundation\Bootstrap;

use Exception;
use AwesomeCoder\Config\Repository;
use AwesomeCoder\Contracts\Config\Repository as RepositoryContract;
use AwesomeCoder\Contracts\Foundation\Application;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return void
     */
    public function bootstrap(Application $plugin)
    {
        $items = [];

        // First we will see if we have a cache configuration file. If we do, we'll load
        // the configuration items from that file so that it is very quick. Otherwise
        // we will need to spin through every configuration file and load them all.
        if (file_exists($cached = $plugin->getCachedConfigPath())) {
            $items = require $cached;

            $loadedFromCache = true;
        }

        // Next we will spin through all of the configuration files in the configuration
        // directory and load each one into the repository. This will make all of the
        // options available to the developer for use in various parts of this app.
        $plugin->instance('config', $config = new Repository($items));

        if (!isset($loadedFromCache)) {
            $this->loadConfigurationFiles($plugin, $config);
        }

        // Finally, we will set the application's environment based on the configuration
        // values that were loaded. We will pass a callback which will be used to get
        // the environment in a web context where an "--env" switch is not present.
        $plugin->detectEnvironment(fn () => $config->get('app.env', 'production'));

        date_default_timezone_set($config->get('app.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @param  \AwesomeCoder\Contracts\Config\Repository  $repository
     * @return void
     *
     * @throws \Exception
     */
    protected function loadConfigurationFiles(Application $plugin, RepositoryContract $repository)
    {
        $files = $this->getConfigurationFiles($plugin);

        if (!isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }

        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return array
     */
    protected function getConfigurationFiles(Application $plugin)
    {
        $files = [];

        $configPath = realpath($plugin->configPath());

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory . basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested) . '.';
        }

        return $nested;
    }
}
