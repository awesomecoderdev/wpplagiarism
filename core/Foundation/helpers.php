<?php

use AwesomeCoder\Container\Container;
use AwesomeCoder\Contracts\Routing\ResponseFactory;
use AwesomeCoder\Foundation\Mix;
use AwesomeCoder\Support\Facades\Date;
use AwesomeCoder\Support\HtmlString;


if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\AwesomeCoder\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @return string
     */
    function asset($path)
    {
        if (defined("PLAGIARISM_PATH")) {
            return PLAGIARISM_PATH . "assets/$path";
        } else {
            return "wp-content/plugins/wp-plagiarism/assets/$path";
        }
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
        return app(Mix::class)(...func_get_args());
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
        return app('request')->old($key, $default);
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
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
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
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     */
    function url($path = null, $parameters = [])
    {
        $params = http_build_query($parameters);

        if (!is_null($path)) {
            if (defined("PLAGIARISM_URL")) {
                $path = PLAGIARISM_URL . "assets/$path";
            } else {
                $path = "wp-content/plugins/wp-plagiarism/$path";
            }
        } else {
            $path = "wp-content/plugins/wp-plagiarism/";
        }

        if (strpos($path, "?") !== false) {
            $path = "$path&";
        } else {
            $path = "$path?";
        }

        return $path . $params;
    }
}

if (!function_exists('dd')) {
    /**
     * @return never
     */
    function dd(...$vars): void
    {
        if (!in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        foreach ($vars as $v) {
            echo "<pre>";
            print_r($v);
            echo "</pre>";
        }

        exit(1);
    }
}
