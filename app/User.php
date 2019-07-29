<?php

namespace App;

use App\Koalaboox\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property \App\Koalaboox\Client api
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'api_token_expires_at', 'api_token_refresh',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var
     */
    protected $api;

    /**
     * Return the Koalaboox authenticated for the user.
     *
     * @return mixed
     */
    public function getApiAttribute()
    {
        if (empty($this->api_token)) {
            return;
        }

        if (empty($this->api)) {
            $this->api = app(Client::class)->forUser($this);
        }

        return $this->api;
    }

    /**
     * Clear the token attributes form the user model.
     */
    public function clearTokenAttributes()
    {
        $api_token = $api_token_expires_at = $api_token_refresh = null;

        $this->update(compact('api_token', 'api_token_expires_at', 'api_token_refresh'));
    }
}
