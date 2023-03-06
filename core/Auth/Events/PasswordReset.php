<?php

namespace AwesomeCoder\Auth\Events;

use AwesomeCoder\Queue\SerializesModels;

class PasswordReset
{
    use SerializesModels;

    /**
     * The user.
     *
     * @var \AwesomeCoder\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \AwesomeCoder\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
