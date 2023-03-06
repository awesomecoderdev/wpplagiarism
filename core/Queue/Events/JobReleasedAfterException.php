<?php

namespace AwesomeCoder\Queue\Events;

class JobReleasedAfterException
{
    /**
     * The connection name.
     *
     * @var string
     */
    public $connectionName;

    /**
     * The job instance.
     *
     * @var \AwesomeCoder\Contracts\Queue\Job
     */
    public $job;

    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName
     * @param  \AwesomeCoder\Contracts\Queue\Job  $job
     * @return void
     */
    public function __construct($connectionName, $job)
    {
        $this->job = $job;
        $this->connectionName = $connectionName;
    }
}
