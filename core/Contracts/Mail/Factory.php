<?php

namespace AwesomeCoder\Contracts\Mail;

interface Factory
{
    /**
     * Get a mailer instance by name.
     *
     * @param  string|null  $name
     * @return \AwesomeCoder\Contracts\Mail\Mailer
     */
    public function mailer($name = null);
}
