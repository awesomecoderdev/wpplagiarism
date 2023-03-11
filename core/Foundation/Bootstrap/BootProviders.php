<?php

namespace AwesomeCoder\Foundation\Bootstrap;

use AwesomeCoder\Contracts\Foundation\Application;

class BootProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->boot();
    }
}
