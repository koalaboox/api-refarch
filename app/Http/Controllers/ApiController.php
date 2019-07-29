<?php

namespace App\Http\Controllers;

use App\Koalaboox\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    /**
     * @var \App\Koalaboox\Client
     */
    protected $api;

    /**
     * @var string
     */
    protected $stateSessionKey = 'state';

    /**
     * ApiController constructor.
     *
     * @param \App\Koalaboox\Client $api
     */
    public function __construct(Client $api)
    {
        $this->api = $api;

        $this->middleware('api.state')->only('middleware');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request)
    {
        $request->session()->put($this->stateSessionKey, $state = Str::random());

        $url = $this->api->getRedirectUri($state);

        return redirect()->to($url);
    }

    /**
     * Handle the call-back from the authorization route.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function callback(Request $request)
    {
        $redirect = redirect()->route('home');

        if ($request->has('error')) {
            if ($request->get('error') == 'access_denied') {
                $message = 'You refused to connect to Koalaboox API';
            } else {
                $message = 'Could not connect to Koalaboox API: Unknown error';
            }

            return $redirect->withError($message);
        }

        if (empty($code = $request->get('code'))) {
            return $redirect->withError('Unknown error');
        }

        if ($this->api->getUserApiKey($request->user(), $code)) {
            return $redirect->withSuccess('Connection successfully established');
        } else {
            return $redirect->withErrore('Could not connect to Koalaboox API: Unknown error');
        }
    }
}
