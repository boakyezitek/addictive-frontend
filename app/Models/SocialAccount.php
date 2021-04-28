<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Facades\Socialite;

class SocialAccount extends Model
{
    use Eventable;

    /**
     * Get the user for this account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set provider attribute from the token.
     *
     * @param string $provider
     * @param string $token
     *
     * @return self
     */
    public function setProviderId($provider, $token)
    {
        $this->provider_id = Socialite::driver($provider)->userFromToken($token)->getId();

        return $this;
    }

    /**
     * Initialize a new social account.
     *
     * @param string $provider_name
     * @param string $token
     *
     * @return self
     */
    public function register($provider_name, $token)
    {
        $this->provider_name = $provider_name;
        if($provider_name == 'google'){
            $this->provider_id = $token;
        } else {
            $this->setProviderId($provider_name, $token);
        }

        return $this;
    }
}
