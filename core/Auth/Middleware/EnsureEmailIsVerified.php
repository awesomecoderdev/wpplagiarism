<?php

namespace AwesomeCoder\Auth\Middleware;

use Closure;
use AwesomeCoder\Contracts\Auth\MustVerifyEmail;
use AwesomeCoder\Support\Facades\Redirect;
use AwesomeCoder\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \AwesomeCoder\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \AwesomeCoder\Http\Response|\AwesomeCoder\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
