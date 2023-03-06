<?php

namespace AwesomeCoder\Foundation;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        static::clearCompiled();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        static::clearCompiled();
    }

    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        static::clearCompiled();
    }

    /**
     * Clear the cached Wordpress Plugin bootstrapping files.
     *
     * @return void
     */
    protected static function clearCompiled()
    {
        $plugin = new Application(getcwd());

        if (is_file($configPath = $plugin->getCachedConfigPath())) {
            @unlink($configPath);
        }

        if (is_file($servicesPath = $plugin->getCachedServicesPath())) {
            @unlink($servicesPath);
        }

        if (is_file($packagesPath = $plugin->getCachedPackagesPath())) {
            @unlink($packagesPath);
        }
    }
}
