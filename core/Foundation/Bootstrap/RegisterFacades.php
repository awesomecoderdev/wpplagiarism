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
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);

        AliasLoader::getInstance(array_merge(
            $app->make('config')->get('app.aliases', []),
            $app->make(PackageManifest::class)->aliases()
        ))->register();
    }
}
