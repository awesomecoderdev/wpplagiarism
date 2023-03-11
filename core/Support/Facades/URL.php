<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static string full()
 * @method static string current()
 * @method static string previous(mixed $fallback = false)
 * @method static string previousPath(mixed $fallback = false)
 * @method static string to(string $path, mixed $extra = [], bool|null $secure = null)
 * @method static string secure(string $path, array $parameters = [])
 * @method static string asset(string $path, bool|null $secure = null)
 * @method static string secureAsset(string $path)
 * @method static string assetFrom(string $root, string $path, bool|null $secure = null)
 * @method static string formatScheme(bool|null $secure = null)
 * @method static string signedRoute(string $name, mixed $parameters = [], \DateTimeInterface|\DateInterval|int|null $expiration = null, bool $absolute = true)
 * @method static string temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [], bool $absolute = true)
 * @method static bool hasValidSignature(\AwesomeCoder\Http\Request $request, bool $absolute = true, array $ignoreQuery = [])
 * @method static bool hasValidRelativeSignature(\AwesomeCoder\Http\Request $request, array $ignoreQuery = [])
 * @method static bool hasCorrectSignature(\AwesomeCoder\Http\Request $request, bool $absolute = true, array $ignoreQuery = [])
 * @method static bool signatureHasNotExpired(\AwesomeCoder\Http\Request $request)
 * @method static string route(string $name, mixed $parameters = [], bool $absolute = true)
 * @method static string toRoute(\AwesomeCoder\Routing\Route $route, mixed $parameters, bool $absolute)
 * @method static string action(string|array $action, mixed $parameters = [], bool $absolute = true)
 * @method static array formatParameters(mixed|array $parameters)
 * @method static string formatRoot(string $scheme, string|null $root = null)
 * @method static string format(string $root, string $path, \AwesomeCoder\Routing\Route|null $route = null)
 * @method static bool isValidUrl(string $path)
 * @method static void defaults(array $defaults)
 * @method static array getDefaultParameters()
 * @method static void forceScheme(string|null $scheme)
 * @method static void forceRootUrl(string|null $root)
 * @method static \AwesomeCoder\Routing\UrlGenerator formatHostUsing(\Closure $callback)
 * @method static \AwesomeCoder\Routing\UrlGenerator formatPathUsing(\Closure $callback)
 * @method static \Closure pathFormatter()
 * @method static \AwesomeCoder\Http\Request getRequest()
 * @method static void setRequest(\AwesomeCoder\Http\Request $request)
 * @method static \AwesomeCoder\Routing\UrlGenerator setRoutes(\AwesomeCoder\Routing\RouteCollectionInterface $routes)
 * @method static \AwesomeCoder\Routing\UrlGenerator setSessionResolver(callable $sessionResolver)
 * @method static \AwesomeCoder\Routing\UrlGenerator setKeyResolver(callable $keyResolver)
 * @method static \AwesomeCoder\Routing\UrlGenerator withKeyResolver(callable $keyResolver)
 * @method static string getRootControllerNamespace()
 * @method static \AwesomeCoder\Routing\UrlGenerator setRootControllerNamespace(string $rootNamespace)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \AwesomeCoder\Routing\UrlGenerator
 */
class URL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'url';
    }
}
