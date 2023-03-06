<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static \AwesomeCoder\Http\RedirectResponse back(int $status = 302, array $headers = [], mixed $fallback = false)
 * @method static \AwesomeCoder\Http\RedirectResponse refresh(int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse guest(string $path, int $status = 302, array $headers = [], bool|null $secure = null)
 * @method static \AwesomeCoder\Http\RedirectResponse intended(mixed $default = '/', int $status = 302, array $headers = [], bool|null $secure = null)
 * @method static \AwesomeCoder\Http\RedirectResponse to(string $path, int $status = 302, array $headers = [], bool|null $secure = null)
 * @method static \AwesomeCoder\Http\RedirectResponse away(string $path, int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse secure(string $path, int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse route(string $route, mixed $parameters = [], int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse signedRoute(string $route, mixed $parameters = [], \DateTimeInterface|\DateInterval|int|null $expiration = null, int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse temporarySignedRoute(string $route, \DateTimeInterface|\DateInterval|int|null $expiration, mixed $parameters = [], int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Http\RedirectResponse action(string|array $action, mixed $parameters = [], int $status = 302, array $headers = [])
 * @method static \AwesomeCoder\Routing\UrlGenerator getUrlGenerator()
 * @method static void setSession(\AwesomeCoder\Session\Store $session)
 * @method static string|null getIntendedUrl()
 * @method static \AwesomeCoder\Routing\Redirector setIntendedUrl(string $url)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \AwesomeCoder\Routing\Redirector
 */
class Redirect extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'redirect';
    }
}
