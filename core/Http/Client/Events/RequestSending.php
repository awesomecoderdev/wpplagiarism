<?php

namespace AwesomeCoder\Http\Client\Events;

use AwesomeCoder\Http\Client\Request;

class RequestSending
{
    /**
     * The request instance.
     *
     * @var \AwesomeCoder\Http\Client\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \AwesomeCoder\Http\Client\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
