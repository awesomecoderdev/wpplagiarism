<?php

namespace AwesomeCoder\Contracts\Auth;

interface PasswordBrokerFactory
{
    /**
     * Get a password broker instance by name.
     *
     * @param  string|null  $name
     * @return \AwesomeCoder\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null);
}
