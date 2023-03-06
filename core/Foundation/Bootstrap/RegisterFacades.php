<?php

namespace AwesomeCoder\Foundation\Bootstrap;

use AwesomeCoder\Contracts\Foundation\Application;
use AwesomeCoder\Foundation\AliasLoader;
use AwesomeCoder\Foundation\PackageManifest;
use AwesomeCoder\Support\Facades\Facade;

class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return void
     */
    public function bootstrap(Application $plugin)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($plugin);

        AliasLoader::getInstance(array_merge(
            $plugin->make('config')->get('app.aliases', []),
            $plugin->make(PackageManifest::class)->aliases()
        ))->register();
    }
}
