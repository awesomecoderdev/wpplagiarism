<?php

namespace AwesomeCoder\Contracts\Filesystem;

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  string|null  $name
     * @return \AwesomeCoder\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}
