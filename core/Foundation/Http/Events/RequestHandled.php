<?php

namespace AwesomeCoder\Foundation\Http\Events;

class RequestHandled
{
    /**
     * The request instance.
     *
     * @var \AwesomeCoder\Http\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \AwesomeCoder\Http\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \AwesomeCoder\Http\Response  $response
     * @return void
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
