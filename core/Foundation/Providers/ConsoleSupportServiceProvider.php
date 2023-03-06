<?php

namespace AwesomeCoder\Foundation\Providers;

use AwesomeCoder\Contracts\Support\DeferrableProvider;
use AwesomeCoder\Database\MigrationServiceProvider;
use AwesomeCoder\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
