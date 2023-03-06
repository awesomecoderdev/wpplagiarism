<?php

namespace AwesomeCoder\Queue\Connectors;

interface ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \AwesomeCoder\Contracts\Queue\Queue
     */
    public function connect(array $config);
}
