<?php

namespace App\Koalaboox;

use App\Koalaboox\Exceptions\InvalidTokenException;
use App\Koalaboox\Exceptions\MissingTokenException;
use App\User;
use function GuzzleHttp\Promise\rejection_for;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;

class AuthenticateRequest
{
    /**
     * Invoke the middleware.
     *
     * @param $next
     * @return \Closure
     */
    public function __invoke($next)
    {
        return function (RequestInterface $request, array $options) use ($next) {

            // Try to authenticate the request with the token of the auth_user.
            if (Arr::get($options, 'auth_user') instanceof User && !empty($token = $options['auth_user']->api_token)) {
                $request = $request->withHeader('Authorization', "Bearer {$token}");
            } else {
                $token = null;
            }

            // Force Laravel to only return JSON responses and not re-directions.
            $request = $request->withHeader('Accept', 'application/json');

            return $next($request, $options)->then(function ($value) use ($token) {
                // In case there was a token exception, we throw a custom exception.
                if ($value instanceof Response && $value->getStatusCode() == 401) {
                    if (empty($token)) {
                        // If there was an error and the token is not defined, it means that it should have been present
                        // and that it is missing.
                        throw new MissingTokenException('Missing Token');
                    } else {
                        // Token present, but invalid.
                        throw new InvalidTokenException('Invalid Token');
                    }
                }

                return $value;
            }, function ($reason) {
                return rejection_for($reason);
            });
        };
    }
}