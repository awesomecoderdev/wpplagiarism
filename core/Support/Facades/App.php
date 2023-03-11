<?php

namespace AwesomeCoder\Support\Facades;

/**
 * @method static string version()
 * @method static void bootstrapWith(string[] $bootstrappers)
 * @method static void afterLoadingEnvironment(\Closure $callback)
 * @method static void beforeBootstrapping(string $bootstrapper, \Closure $callback)
 * @method static void afterBootstrapping(string $bootstrapper, \Closure $callback)
 * @method static bool hasBeenBootstrapped()
 * @method static \AwesomeCoder\Foundation\Application setBasePath(string $basePath)
 * @method static string path(string $path = '')
 * @method static \AwesomeCoder\Foundation\Application useAppPath(string $path)
 * @method static string basePath(string $path = '')
 * @method static string bootstrapPath(string $path = '')
 * @method static string configPath(string $path = '')
 * @method static string databasePath(string $path = '')
 * @method static \AwesomeCoder\Foundation\Application useDatabasePath(string $path)
 * @method static string langPath(string $path = '')
 * @method static \AwesomeCoder\Foundation\Application useLangPath(string $path)
 * @method static string publicPath()
 * @method static string storagePath(string $path = '')
 * @method static \AwesomeCoder\Foundation\Application useStoragePath(string $path)
 * @method static string resourcePath(string $path = '')
 * @method static string viewPath(string $path = '')
 * @method static string environmentPath()
 * @method static \AwesomeCoder\Foundation\Application useEnvironmentPath(string $path)
 * @method static \AwesomeCoder\Foundation\Application loadEnvironmentFrom(string $file)
 * @method static string environmentFile()
 * @method static string environmentFilePath()
 * @method static string|bool environment(string|array ...$environments)
 * @method static bool isLocal()
 * @method static bool isProduction()
 * @method static string detectEnvironment(\Closure $callback)
 * @method static bool runningInConsole()
 * @method static bool runningUnitTests()
 * @method static bool hasDebugModeEnabled()
 * @method static void registerConfiguredProviders()
 * @method static \AwesomeCoder\Support\ServiceProvider register(\AwesomeCoder\Support\ServiceProvider|string $provider, bool $force = false)
 * @method static \AwesomeCoder\Support\ServiceProvider|null getProvider(\AwesomeCoder\Support\ServiceProvider|string $provider)
 * @method static array getProviders(\AwesomeCoder\Support\ServiceProvider|string $provider)
 * @method static \AwesomeCoder\Support\ServiceProvider resolveProvider(string $provider)
 * @method static void loadDeferredProviders()
 * @method static void loadDeferredProvider(string $service)
 * @method static void registerDeferredProvider(string $provider, string|null $service = null)
 * @method static mixed make(string $abstract, array $parameters = [])
 * @method static bool bound(string $abstract)
 * @method static bool isBooted()
 * @method static void boot()
 * @method static void booting(callable $callback)
 * @method static void booted(callable $callback)
 * @method static \Symfony\Component\HttpFoundation\Response handle(\Symfony\Component\HttpFoundation\Request $request, int $type = 1, bool $catch = true)
 * @method static bool shouldSkipMiddleware()
 * @method static string getCachedServicesPath()
 * @method static string getCachedPackagesPath()
 * @method static bool configurationIsCached()
 * @method static string getCachedConfigPath()
 * @method static bool routesAreCached()
 * @method static string getCachedRoutesPath()
 * @method static bool eventsAreCached()
 * @method static string getCachedEventsPath()
 * @method static \AwesomeCoder\Foundation\Application addAbsoluteCachePathPrefix(string $prefix)
 * @method static \AwesomeCoder\Contracts\Foundation\MaintenanceMode maintenanceMode()
 * @method static bool isDownForMaintenance()
 * @method static never abort(int $code, string $message = '', array $headers = [])
 * @method static \AwesomeCoder\Foundation\Application terminating(callable|string $callback)
 * @method static void terminate()
 * @method static array getLoadedProviders()
 * @method static bool providerIsLoaded(string $provider)
 * @method static array getDeferredServices()
 * @method static void setDeferredServices(array $services)
 * @method static void addDeferredServices(array $services)
 * @method static bool isDeferredService(string $service)
 * @method static void provideFacades(string $namespace)
 * @method static string getLocale()
 * @method static string currentLocale()
 * @method static string getFallbackLocale()
 * @method static void setLocale(string $locale)
 * @method static void setFallbackLocale(string $fallbackLocale)
 * @method static bool isLocale(string $locale)
 * @method static void registerCoreContainerAliases()
 * @method static void flush()
 * @method static string getNamespace()
 * @method static \AwesomeCoder\Contracts\Container\ContextualBindingBuilder when(array|string $concrete)
 * @method static bool has(string $id)
 * @method static bool isShared(string $abstract)
 * @method static bool isAlias(string $name)
 * @method static void bind(string $abstract, \Closure|string|null $concrete = null, bool $shared = false)
 * @method static bool hasMethodBinding(string $method)
 * @method static void bindMethod(array|string $method, \Closure $callback)
 * @method static mixed callMethodBinding(string $method, mixed $instance)
 * @method static void addContextualBinding(string $concrete, string $abstract, \Closure|string $implementation)
 * @method static void bindIf(string $abstract, \Closure|string|null $concrete = null, bool $shared = false)
 * @method static void singleton(string $abstract, \Closure|string|null $concrete = null)
 * @method static void singletonIf(string $abstract, \Closure|string|null $concrete = null)
 * @method static void scoped(string $abstract, \Closure|string|null $concrete = null)
 * @method static void scopedIf(string $abstract, \Closure|string|null $concrete = null)
 * @method static void extend(string $abstract, \Closure $closure)
 * @method static mixed instance(string $abstract, mixed $instance)
 * @method static void tag(array|string $abstracts, array|mixed $tags)
 * @method static iterable tagged(string $tag)
 * @method static void alias(string $abstract, string $alias)
 * @method static mixed rebinding(string $abstract, \Closure $callback)
 * @method static mixed refresh(string $abstract, mixed $target, string $method)
 * @method static \Closure wrap(\Closure $callback, array $parameters = [])
 * @method static mixed call(callable|string $callback, array $parameters = [], string|null $defaultMethod = null)
 * @method static \Closure factory(string $abstract)
 * @method static mixed makeWith(string|callable $abstract, array $parameters = [])
 * @method static mixed get(string $id)
 * @method static mixed build(\Closure|string $concrete)
 * @method static void beforeResolving(\Closure|string $abstract, \Closure|null $callback = null)
 * @method static void resolving(\Closure|string $abstract, \Closure|null $callback = null)
 * @method static void afterResolving(\Closure|string $abstract, \Closure|null $callback = null)
 * @method static array getBindings()
 * @method static string getAlias(string $abstract)
 * @method static void forgetExtenders(string $abstract)
 * @method static void forgetInstance(string $abstract)
 * @method static void forgetInstances()
 * @method static void forgetScopedInstances()
 * @method static \AwesomeCoder\Foundation\Application getInstance()
 * @method static \AwesomeCoder\Contracts\Container\Container|\AwesomeCoder\Foundation\Application setInstance(\AwesomeCoder\Contracts\Container\Container|null $container = null)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \AwesomeCoder\Foundation\Application
 */
class App extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'app';
    }
}
