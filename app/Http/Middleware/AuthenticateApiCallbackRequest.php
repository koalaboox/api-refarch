<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateApiCallbackRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->has('state')) {
            $this->abort('State not found in request');
        } elseif (!$request->session()->has('state')) {
            $this->abort('State not found in session');
        } elseif ($request->get('state') != $request->session()->get('state')) {
            $this->abort('State mismatch');
        }

        return $next($request);
    }

    /**
     * Abort the current request.
     *
     * @param string $reson
     */
    public function abort($reason)
    {
        abort(422, "Invalid request ({$reason})");
    }
}
