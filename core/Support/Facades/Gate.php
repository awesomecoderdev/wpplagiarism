<?php

namespace AwesomeCoder\Support\Facades;

use AwesomeCoder\Contracts\Auth\Access\Gate as GateContract;

/**
 * @method static bool has(string|array $ability)
 * @method static \AwesomeCoder\Auth\Access\Response allowIf(\AwesomeCoder\Auth\Access\Response|\Closure|bool $condition, string|null $message = null, string|null $code = null)
 * @method static \AwesomeCoder\Auth\Access\Response denyIf(\AwesomeCoder\Auth\Access\Response|\Closure|bool $condition, string|null $message = null, string|null $code = null)
 * @method static \AwesomeCoder\Auth\Access\Gate define(string $ability, callable|array|string $callback)
 * @method static \AwesomeCoder\Auth\Access\Gate resource(string $name, string $class, array|null $abilities = null)
 * @method static \AwesomeCoder\Auth\Access\Gate policy(string $class, string $policy)
 * @method static \AwesomeCoder\Auth\Access\Gate before(callable $callback)
 * @method static \AwesomeCoder\Auth\Access\Gate after(callable $callback)
 * @method static bool allows(string $ability, array|mixed $arguments = [])
 * @method static bool denies(string $ability, array|mixed $arguments = [])
 * @method static bool check(iterable|string $abilities, array|mixed $arguments = [])
 * @method static bool any(iterable|string $abilities, array|mixed $arguments = [])
 * @method static bool none(iterable|string $abilities, array|mixed $arguments = [])
 * @method static \AwesomeCoder\Auth\Access\Response authorize(string $ability, array|mixed $arguments = [])
 * @method static \AwesomeCoder\Auth\Access\Response inspect(string $ability, array|mixed $arguments = [])
 * @method static mixed raw(string $ability, array|mixed $arguments = [])
 * @method static mixed getPolicyFor(object|string $class)
 * @method static \AwesomeCoder\Auth\Access\Gate guessPolicyNamesUsing(callable $callback)
 * @method static mixed resolvePolicy(object|string $class)
 * @method static \AwesomeCoder\Auth\Access\Gate forUser(\AwesomeCoder\Contracts\Auth\Authenticatable|mixed $user)
 * @method static array abilities()
 * @method static array policies()
 * @method static \AwesomeCoder\Auth\Access\Gate setContainer(\AwesomeCoder\Contracts\Container\Container $container)
 * @method static \AwesomeCoder\Auth\Access\Response denyWithStatus(int $status, string|null $message = null, int|null $code = null)
 * @method static \AwesomeCoder\Auth\Access\Response denyAsNotFound(string|null $message = null, int|null $code = null)
 *
 * @see \AwesomeCoder\Auth\Access\Gate
 */
class Gate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GateContract::class;
    }
}
