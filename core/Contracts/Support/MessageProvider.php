<?php

namespace AwesomeCoder\Contracts\Support;

interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \AwesomeCoder\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
