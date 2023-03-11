<?php

namespace AwesomeCoder\Foundation\Auth;

use AwesomeCoder\Auth\Authenticatable;
use AwesomeCoder\Auth\MustVerifyEmail;
use AwesomeCoder\Auth\Passwords\CanResetPassword;
use AwesomeCoder\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use AwesomeCoder\Contracts\Auth\Authenticatable as AuthenticatableContract;
use AwesomeCoder\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use AwesomeCoder\Database\Eloquent\Model;
use AwesomeCoder\Foundation\Auth\Access\Authorizable;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
}
