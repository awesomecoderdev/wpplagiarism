<?php

namespace AwesomeCoder\Http\Middleware;

use AwesomeCoder\Contracts\Foundation\Application;
use AwesomeCoder\Http\Request;

abstract class TrustHosts
{
    /**
     * The application instance.
     *
     * @var \AwesomeCoder\Contracts\Foundation\Application
     */
    protected $plugin;

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
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    abstract public function hosts();

    /**
     * Handle the incoming request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \Closure  $next
     * @return \AwesomeCoder\Http\Response
     */
    public function handle(Request $request, $next)
    {
        if ($this->shouldSpecifyTrustedHosts()) {
            Request::setTrustedHosts(array_filter($this->hosts()));
        }

        return $next($request);
    }

    /**
     * Determine if the application should specify trusted hosts.
     *
     * @return bool
     */
    protected function shouldSpecifyTrustedHosts()
    {
        return !$this->app->environment('local') &&
            !$this->app->runningUnitTests();
    }

    /**
     * Get a regular expression matching the application URL and all of its subdomains.
     *
     * @return string|null
     */
    protected function allSubdomainsOfApplicationUrl()
    {
        if ($host = parse_url($this->app['config']->get('app.url'), PHP_URL_HOST)) {
            return '^(.+\.)?' . preg_quote($host) . '$';
        }
    }
}
