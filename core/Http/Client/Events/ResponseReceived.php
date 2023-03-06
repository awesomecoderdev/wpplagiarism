<?php

namespace AwesomeCoder\Http\Client\Events;

use AwesomeCoder\Http\Client\Request;
use AwesomeCoder\Http\Client\Response;

class ResponseReceived
{
    /**
     * The request instance.
     *
     * @var \AwesomeCoder\Http\Client\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \AwesomeCoder\Http\Client\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \AwesomeCoder\Http\Client\Request  $request
     * @param  \AwesomeCoder\Http\Client\Response  $response
     * @return void
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
