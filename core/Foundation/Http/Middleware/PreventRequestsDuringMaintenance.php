<?php

namespace AwesomeCoder\Foundation\Http\Middleware;

use Closure;
use AwesomeCoder\Contracts\Foundation\Application;
use AwesomeCoder\Foundation\Http\MaintenanceModeBypassCookie;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PreventRequestsDuringMaintenance
{
    /**
     * The application implementation.
     *
     * @var \AwesomeCoder\Contracts\Foundation\Application
     */
    protected $plugin;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Create a new middleware instance.
     *
     * @param  \AwesomeCoder\Contracts\Foundation\Application  $plugin
     * @return void
     */
    public function __construct(Application $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->maintenanceMode()->active()) {
            $data = $this->app->maintenanceMode()->data();

            if (isset($data['secret']) && $request->path() === $data['secret']) {
                return $this->bypassResponse($data['secret']);
            }

            if (
                $this->hasValidBypassCookie($request, $data) ||
                $this->inExceptArray($request)
            ) {
                return $next($request);
            }

            if (isset($data['redirect'])) {
                $path = $data['redirect'] === '/'
                    ? $data['redirect']
                    : trim($data['redirect'], '/');

                if ($request->path() !== $path) {
                    return redirect($path);
                }
            }

            if (isset($data['template'])) {
                return response(
                    $data['template'],
                    $data['status'] ?? 503,
                    $this->getHeaders($data)
                );
            }

            throw new HttpException(
                $data['status'] ?? 503,
                'Service Unavailable',
                null,
                $this->getHeaders($data)
            );
        }

        return $next($request);
    }

    /**
     * Determine if the incoming request has a maintenance mode bypass cookie.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  array  $data
     * @return bool
     */
    protected function hasValidBypassCookie($request, array $data)
    {
        return isset($data['secret']) &&
            $request->cookie('PLUGIN_maintenance') &&
            MaintenanceModeBypassCookie::isValid(
                $request->cookie('PLUGIN_maintenance'),
                $data['secret']
            );
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->getExcludedPaths() as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Redirect the user back to the root of the application with a maintenance mode bypass cookie.
     *
     * @param  string  $secret
     * @return \AwesomeCoder\Http\RedirectResponse
     */
    protected function bypassResponse(string $secret)
    {
        return redirect('/')->withCookie(
            MaintenanceModeBypassCookie::create($secret)
        );
    }

    /**
     * Get the headers that should be sent with the response.
     *
     * @param  array  $data
     * @return array
     */
    protected function getHeaders($data)
    {
        $headers = isset($data['retry']) ? ['Retry-After' => $data['retry']] : [];

        if (isset($data['refresh'])) {
            $headers['Refresh'] = $data['refresh'];
        }

        return $headers;
    }

    /**
     * Get the URIs that should be accessible even when maintenance mode is enabled.
     *
     * @return array
     */
    public function getExcludedPaths()
    {
        return $this->except;
    }
}
