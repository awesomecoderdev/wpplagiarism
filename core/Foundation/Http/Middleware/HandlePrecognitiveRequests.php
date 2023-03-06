<?php

namespace AwesomeCoder\Foundation\Http\Middleware;

use AwesomeCoder\Container\Container;
use AwesomeCoder\Foundation\Routing\PrecognitionCallableDispatcher;
use AwesomeCoder\Foundation\Routing\PrecognitionControllerDispatcher;
use AwesomeCoder\Http\Response;
use AwesomeCoder\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use AwesomeCoder\Routing\Contracts\ControllerDispatcher as ControllerDispatcherContract;

class HandlePrecognitiveRequests
{
    /**
     * The container instance.
     *
     * @var \AwesomeCoder\Container\Container
     */
    protected $container;

    /**
     * Create a new middleware instance.
     *
     * @param  \AwesomeCoder\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \Closure  $next
     * @return \AwesomeCoder\Http\Response
     */
    public function handle($request, $next)
    {
        if (!$request->isAttemptingPrecognition()) {
            return $this->appendVaryHeader($request, $next($request));
        }

        $this->prepareForPrecognition($request);

        return tap($next($request), function ($response) use ($request) {
            $response->headers->set('Precognition', 'true');

            $this->appendVaryHeader($request, $response);
        });
    }

    /**
     * Prepare to handle a precognitive request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @return void
     */
    protected function prepareForPrecognition($request)
    {
        $request->attributes->set('precognitive', true);

        $this->container->bind(CallableDispatcherContract::class, fn ($plugin) => new PrecognitionCallableDispatcher($plugin));
        $this->container->bind(ControllerDispatcherContract::class, fn ($plugin) => new PrecognitionControllerDispatcher($plugin));
    }

    /**
     * Append the appropriate "Vary" header to the given response.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \AwesomeCoder\Http\Response  $response
     * @return \AwesomeCoder\Http\Response $response
     */
    protected function appendVaryHeader($request, $response)
    {
        return tap($response, fn () => $response->headers->set('Vary', implode(', ', array_filter([
            $response->headers->get('Vary'),
            'Precognition',
        ]))));
    }
}
