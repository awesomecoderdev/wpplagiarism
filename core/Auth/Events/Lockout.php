<?php

namespace AwesomeCoder\Auth\Events;

use AwesomeCoder\Http\Request;

class Lockout
{
    /**
     * The throttled request.
     *
     * @var \AwesomeCoder\Http\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
