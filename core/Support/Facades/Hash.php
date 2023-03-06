<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static \AwesomeCoder\Hashing\BcryptHasher createBcryptDriver()
 * @method static \AwesomeCoder\Hashing\ArgonHasher createArgonDriver()
 * @method static \AwesomeCoder\Hashing\Argon2IdHasher createArgon2idDriver()
 * @method static array info(string $hashedValue)
 * @method static string make(string $value, array $options = [])
 * @method static bool check(string $value, string $hashedValue, array $options = [])
 * @method static bool needsRehash(string $hashedValue, array $options = [])
 * @method static string getDefaultDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \AwesomeCoder\Hashing\HashManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \AwesomeCoder\Contracts\Container\Container getContainer()
 * @method static \AwesomeCoder\Hashing\HashManager setContainer(\AwesomeCoder\Contracts\Container\Container $container)
 * @method static \AwesomeCoder\Hashing\HashManager forgetDrivers()
 *
 * @see \AwesomeCoder\Hashing\HashManager
 * @see \AwesomeCoder\Hashing\AbstractHasher
 */
class Hash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hash';
    }
}
