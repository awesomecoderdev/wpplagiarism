<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static \AwesomeCoder\Validation\Validator make(array $data, array $rules, array $messages = [], array $customAttributes = [])
 * @method static array validate(array $data, array $rules, array $messages = [], array $customAttributes = [])
 * @method static void extend(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void extendImplicit(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void extendDependent(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void replacer(string $rule, \Closure|string $replacer)
 * @method static void includeUnvalidatedArrayKeys()
 * @method static void excludeUnvalidatedArrayKeys()
 * @method static void resolver(\Closure $resolver)
 * @method static \AwesomeCoder\Contracts\Translation\Translator getTranslator()
 * @method static \AwesomeCoder\Validation\PresenceVerifierInterface getPresenceVerifier()
 * @method static void setPresenceVerifier(\AwesomeCoder\Validation\PresenceVerifierInterface $presenceVerifier)
 * @method static \AwesomeCoder\Contracts\Container\Container|null getContainer()
 * @method static \AwesomeCoder\Validation\Factory setContainer(\AwesomeCoder\Contracts\Container\Container $container)
 *
 * @see \AwesomeCoder\Validation\Factory
 */
class Validator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'validator';
    }
}
