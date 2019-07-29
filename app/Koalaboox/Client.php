<?php

namespace App\Koalaboox;

use App\User;
use GuzzleHttp\Exception\RequestException;

class Client
{
    /**
     * The Guzzle client used to handle communication.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $authUser;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param string             $uri
     * @param string             $id
     * @param string             $secret
     */
    public function __construct(\GuzzleHttp\Client $client, $uri, $id, $secret)
    {
        $this->client = $client;
        $this->uri = $uri;
        $this->id = $id;
        $this->secret = $secret;
    }

    /**
     * @param string $state
     * @return string
     */
    public function getRedirectUri($state)
    {
        $params = [
            'client_id' => $this->id,
            'redirect_uri' => url('api/validate'),
            'state' => $state,
            'response_type' => 'code',
            'scope' => '',
        ];

        return $this->uri . '/oauth/authorize?' . http_build_query($params);
    }

    /**
     * Add the token to a user with information exchanged from a callback code.
     *
     * @param \App\User $user
     * @param string    $code
     * @return array|bool
     */
    public function getUserApiKey(User $user, $code)
    {
        if ($token = $this->exchangeApiCodeForToken($code)) {
            $user->update([
                'api_token' => $token['access_token'],
                'api_token_expires_at' => now()->addSeconds($token['expires_in']),
                'api_token_refresh' => $token['refresh_token'],
            ]);

            return $token;
        }
    }

    /**
     * Clear the connection between the user and the API.
     *
     * @param \App\User $user
     */
    public function clearUserApiKey(User $user)
    {
        $api_token = $api_token_expires_at = $api_token_refresh = null;

        $user->update(compact('api_token', 'api_token_expires_at', 'api_token_refresh'));
    }

    /**
     * Exchange the callback code against a token.
     *
     * @param $code
     * @return array|bool
     */
    public function exchangeApiCodeForToken($code)
    {
        try {
            $response = $this->client->post('http://connect.koalaboox.lan/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->id,
                    'client_secret' => $this->secret,
                    'redirect_uri' => url('api/validate'),
                    'code' => $code,
                ],
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            app('log')->error("Could not obtain user token: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Set the user the client will by using to identify itself.
     *
     * @param \App\User $user
     * @return $this
     */
    public function setAuthUser(User $user)
    {
        $this->authUser = $user;

        return $this;
    }

    /**
     * Create a copy of the API client using a user's token.
     *
     * @param \App\User $user
     * @return \App\Koalaboox\Client
     */
    public function forUser(User $user)
    {
        $client = new self($this->client, $this->uri, $this->id, $this->secret);

        return $client->setAuthUser($user);
    }

    /**
     * Send a request to the Koalaboox API.
     *
     * @param string $method
     * @param string $path
     * @param array  $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request($method, $path, array $options = [])
    {
        if (!empty($this->authUser)) {
            $options['auth_user'] = $this->authUser;
        }

        return $this->handleResponse(function () use ($method, $path, $options) {
            return $this->client->request($method, $path, $options);
        });
    }

    /**
     * Handle a standard Koalaboox API response.
     *
     * @param callable $callback
     * @return \Illuminate\Support\Collection
     */
    public function handleResponse(callable $callback)
    {
        try {
            $response = $callback();
        } catch (RequestException $e) {
            $status = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody();

            throw  new \DomainException("Request failed with a status {$status}: $body", 0, $e);
        }

        $decoded = json_decode((string)$response->getBody());

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Could not decode response: " . json_last_error_msg() . "\nOriginal response: \n{$response}");
        }

        if (!is_object($decoded) || !property_exists($decoded, 'data')) {
            throw new \InvalidArgumentException('Invalid response, no data found');
        }

        return is_array($decoded->data) ? collect($decoded->data) : $decoded->data;
    }

    /**
     * Return all the user's invoices.
     *
     * @return mixed
     */
    public function invoices()
    {
        return $this->request('GET', '/api/invoices');
    }

    /**
     * Return an invoice.
     *
     * @param int $id
     * @return mixed
     */
    public function invoice($id)
    {
        return $this->request('GET', "/api/invoices/{$id}");
    }
}