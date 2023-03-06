<?php

use AwesomeCoder\Container\Container;
use AwesomeCoder\Contracts\Auth\Access\Gate;
use AwesomeCoder\Contracts\Auth\Factory as AuthFactory;
use AwesomeCoder\Contracts\Broadcasting\Factory as BroadcastFactory;
use AwesomeCoder\Contracts\Bus\Dispatcher;
use AwesomeCoder\Contracts\Cookie\Factory as CookieFactory;
use AwesomeCoder\Contracts\Debug\ExceptionHandler;
use AwesomeCoder\Contracts\Routing\ResponseFactory;
use AwesomeCoder\Contracts\Routing\UrlGenerator;
use AwesomeCoder\Contracts\Support\Responsable;
use AwesomeCoder\Contracts\Validation\Factory as ValidationFactory;
use AwesomeCoder\Contracts\View\Factory as ViewFactory;
use AwesomeCoder\Foundation\Bus\PendingClosureDispatch;
use AwesomeCoder\Foundation\Bus\PendingDispatch;
use AwesomeCoder\Foundation\Mix;
use AwesomeCoder\Http\Exceptions\HttpResponseException;
use AwesomeCoder\Queue\CallQueuedClosure;
use AwesomeCoder\Routing\Router;
use AwesomeCoder\Support\Facades\Date;
use AwesomeCoder\Support\HtmlString;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  \Symfony\Component\HttpFoundation\Response|\AwesomeCoder\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return never
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof Response) {
            throw new HttpResponseException($code);
        } elseif ($code instanceof Responsable) {
            throw new HttpResponseException($code->toResponse(request()));
        }

        plugin()->abort($code, $message, $headers);
    }
}

if (!function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\AwesomeCoder\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('abort_unless')) {
    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\AwesomeCoder\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_unless($boolean, $code, $message = '', array $headers = [])
    {
        if (!$boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string|array  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return plugin('url')->action($name, $parameters, $absolute);
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\AwesomeCoder\Contracts\Foundation\Application
     */
    function plugin($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return plugin()->path($path);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return plugin('url')->asset($path, $secure);
    }
}

if (!function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return \AwesomeCoder\Contracts\Auth\Factory|\AwesomeCoder\Contracts\Auth\Guard|\AwesomeCoder\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return plugin(AuthFactory::class);
        }

        return plugin(AuthFactory::class)->guard($guard);
    }
}

if (!function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int  $status
     * @param  array  $headers
     * @param  mixed  $fallback
     * @return \AwesomeCoder\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false)
    {
        return plugin('redirect')->back($status, $headers, $fallback);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return plugin()->basePath($path);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return plugin('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (!function_exists('broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param  mixed|null  $event
     * @return \AwesomeCoder\Broadcasting\PendingBroadcast
     */
    function broadcast($event = null)
    {
        return plugin(BroadcastFactory::class)->event($event);
    }
}

if (!function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     * @return mixed|\AwesomeCoder\Cache\CacheManager
     *
     * @throws \InvalidArgumentException
     */
    function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return plugin('cache');
        }

        if (is_string($arguments[0])) {
            return plugin('cache')->get(...$arguments);
        }

        if (!is_array($arguments[0])) {
            throw new InvalidArgumentException(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return plugin('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1] ?? null);
    }
}

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\AwesomeCoder\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return plugin('config');
        }

        if (is_array($key)) {
            return plugin('config')->set($key);
        }

        return plugin('config')->get($key, $default);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return plugin()->configPath($path);
    }
}

if (!function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param  string|null  $name
     * @param  string|null  $value
     * @param  int  $minutes
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool|null  $secure
     * @param  bool  $httpOnly
     * @param  bool  $raw
     * @param  string|null  $sameSite
     * @return \AwesomeCoder\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = plugin(CookieFactory::class);

        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \AwesomeCoder\Support\HtmlString
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="' . csrf_token() . '">');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = plugin('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string  $path
     * @return string
     */
    function database_path($path = '')
    {
        return plugin()->databasePath($path);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @param  bool  $unserialize
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return plugin('encrypter')->decrypt($value, $unserialize);
    }
}

if (!function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return \AwesomeCoder\Foundation\Bus\PendingDispatch
     */
    function dispatch($job)
    {
        return $job instanceof Closure
            ? new PendingClosureDispatch(CallQueuedClosure::create($job))
            : new PendingDispatch($job);
    }
}

if (!function_exists('dispatch_sync')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * Queueable jobs will be dispatched to the "sync" queue.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     */
    function dispatch_sync($job, $handler = null)
    {
        return plugin(Dispatcher::class)->dispatchSync($job, $handler);
    }
}

if (!function_exists('dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     *
     * @deprecated Will be removed in a future Wordpress Plugin version.
     */
    function dispatch_now($job, $handler = null)
    {
        return plugin(Dispatcher::class)->dispatchNow($job, $handler);
    }
}

if (!function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return plugin('encrypter')->encrypt($value, $serialize);
    }
}

if (!function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    function event(...$args)
    {
        return plugin('events')->dispatch(...$args);
    }
}

if (!function_exists('fake') && class_exists(\Faker\Factory::class)) {
    /**
     * Get a faker instance.
     *
     * @param  string|null  $locale
     * @return \Faker\Generator
     */
    function fake($locale = null)
    {
        if (plugin()->bound('config')) {
            $locale ??= plugin('config')->get('app.faker_locale');
        }

        $locale ??= 'en_US';

        $abstract = \Faker\Generator::class . ':' . $locale;

        if (!plugin()->bound($abstract)) {
            plugin()->singleton($abstract, fn () => \Faker\Factory::create($locale));
        }

        return plugin()->make($abstract);
    }
}

if (!function_exists('info')) {
    /**
     * Write some information to the log.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    function info($message, $context = [])
    {
        plugin('log')->info($message, $context);
    }
}

if (!function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return \AwesomeCoder\Log\LogManager|null
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return plugin('log');
        }

        return plugin('log')->debug($message, $context);
    }
}

if (!function_exists('lang_path')) {
    /**
     * Get the path to the language folder.
     *
     * @param  string  $path
     * @return string
     */
    function lang_path($path = '')
    {
        return plugin()->langPath($path);
    }
}

if (!function_exists('logs')) {
    /**
     * Get a log driver instance.
     *
     * @param  string|null  $driver
     * @return \AwesomeCoder\Log\LogManager|\Psr\Log\LoggerInterface
     */
    function logs($driver = null)
    {
        return $driver ? plugin('log')->driver($driver) : plugin('log');
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param  string  $method
     * @return \AwesomeCoder\Support\HtmlString
     */
    function method_field($method)
    {
        return new HtmlString('<input type="hidden" name="_method" value="' . $method . '">');
    }
}

if (!function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \AwesomeCoder\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        return plugin(Mix::class)(...func_get_args());
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \AwesomeCoder\Support\Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return plugin('request')->old($key, $default);
    }
}

if (!function_exists('policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param  object|string  $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    function policy($class)
    {
        return plugin(Gate::class)->getPolicyFor($class);
    }
}

if (!function_exists('precognitive')) {
    /**
     * Handle a Precognition controller hook.
     *
     * @param  null|callable  $callable
     * @return mixed
     */
    function precognitive($callable = null)
    {
        $callable ??= function () {
            //
        };

        $payload = $callable(function ($default, $precognition = null) {
            $response = request()->isPrecognitive()
                ? ($precognition ?? $default)
                : $default;

            abort(Router::toResponse(request(), value($response)));
        });

        if (request()->isPrecognitive()) {
            abort(204);
        }

        return $payload;
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return plugin()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \AwesomeCoder\Routing\Redirector|\AwesomeCoder\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (is_null($to)) {
            return plugin('redirect');
        }

        return plugin('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('report')) {
    /**
     * Report an exception.
     *
     * @param  \Throwable|string  $exception
     * @return void
     */
    function report($exception)
    {
        if (is_string($exception)) {
            $exception = new Exception($exception);
        }

        plugin(ExceptionHandler::class)->report($exception);
    }
}

if (!function_exists('report_if')) {
    /**
     * Report an exception if the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Throwable|string  $exception
     * @return void
     */
    function report_if($boolean, $exception)
    {
        if ($boolean) {
            report($exception);
        }
    }
}

if (!function_exists('report_unless')) {
    /**
     * Report an exception unless the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Throwable|string  $exception
     * @return void
     */
    function report_unless($boolean, $exception)
    {
        if (!$boolean) {
            report($exception);
        }
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\AwesomeCoder\Http\Request|string|array|null
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return plugin('request');
        }

        if (is_array($key)) {
            return plugin('request')->only($key);
        }

        $value = plugin('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param  callable  $callback
     * @param  mixed  $rescue
     * @param  bool|callable  $report
     * @return mixed
     */
    function rescue(callable $callback, $rescue = null, $report = true)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if (value($report, $e)) {
                report($e);
            }

            return value($rescue, $e);
        }
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     */
    function resolve($name, array $parameters = [])
    {
        return plugin($name, $parameters);
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return plugin()->resourcePath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param  \AwesomeCoder\Contracts\View\View|string|array|null  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \AwesomeCoder\Http\Response|\AwesomeCoder\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = plugin(ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (!function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        return plugin('url')->route($name, $parameters, $absolute);
    }
}

if (!function_exists('secure_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @return string
     */
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param  string  $path
     * @param  mixed  $parameters
     * @return string
     */
    function secure_url($path, $parameters = [])
    {
        return url($path, $parameters, true);
    }
}

if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\AwesomeCoder\Session\Store|\AwesomeCoder\Session\SessionManager
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return plugin('session');
        }

        if (is_array($key)) {
            return plugin('session')->put($key);
        }

        return plugin('session')->get($key, $default);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return plugin()->storagePath($path);
    }
}

if (!function_exists('to_route')) {
    /**
     * Create a new redirect response to a named route.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \AwesomeCoder\Http\RedirectResponse
     */
    function to_route($route, $parameters = [], $status = 302, $headers = [])
    {
        return redirect()->route($route, $parameters, $status, $headers);
    }
}

if (!function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \AwesomeCoder\Support\Carbon
     */
    function today($tz = null)
    {
        return Date::today($tz);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \AwesomeCoder\Contracts\Translation\Translator|string|array|null
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return plugin('translator');
        }

        return plugin('translator')->get($key, $replace, $locale);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return plugin('translator')->choice($key, $number, $replace, $locale);
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return trans($key, $replace, $locale);
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \AwesomeCoder\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return plugin(UrlGenerator::class);
        }

        return plugin(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}

if (!function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \AwesomeCoder\Contracts\Validation\Validator|\AwesomeCoder\Contracts\Validation\Factory
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = plugin(ValidationFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}

if (!function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \AwesomeCoder\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \AwesomeCoder\Contracts\View\View|\AwesomeCoder\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = plugin(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}
