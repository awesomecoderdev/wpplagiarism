<?php

namespace AwesomeCoder\Contracts\Queue;

interface Factory
{
    /**
     * Resolve a queue connection instance.
     *
     * @param  string|null  $name
     * @return \AwesomeCoder\Contracts\Queue\Queue
     */
    public function connection($name = null);
}
