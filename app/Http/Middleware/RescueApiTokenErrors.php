<?php

namespace App\Http\Middleware;

use App\Events\InvalidToken;
use App\Koalaboox\Exceptions\InvalidTokenException;
use Closure;

class RescueApiTokenErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!empty($response->exception) && $response->exception instanceof InvalidTokenException) {
            event(new InvalidToken($request->user()));

            return redirect()->route('home')->withError('Your connection token is not valid anymore. Please reconnect to Koalaboox API');
        }

        return $response;
    }
}
