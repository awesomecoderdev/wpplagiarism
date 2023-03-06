<?php

namespace AwesomeCoder\Http\Middleware;

use AwesomeCoder\Support\Collection;
use AwesomeCoder\Support\Facades\Vite;

class AddLinkHeadersForPreloadedAssets
{
    /**
     * Handle the incoming request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \Closure  $next
     * @return \AwesomeCoder\Http\Response
     */
    public function handle($request, $next)
    {
        return tap($next($request), function ($response) {
            if (Vite::preloadedAssets() !== []) {
                $response->header('Link', Collection::make(Vite::preloadedAssets())
                    ->map(fn ($attributes, $url) => "<{$url}>; " . implode('; ', $attributes))
                    ->join(', '));
            }
        });
    }
}
