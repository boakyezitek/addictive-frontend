<?php

namespace App\Transformers\V1;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        '',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
           'id' => (int) $user->id,
           'name' => $user->username,
           'hash_id' => $user->hash_id,
           'email' => $user->email,
           'subscribed' => $user->subscriptions()->available()->first() ? true : false,
           'is_listening' => $user->is_listening == 0 ? false : true,
           'notification' => json_decode($user->push_settings) ? json_decode($user->push_settings)->general : null,
           'email_verified' => $user->email_verified_at ? true : false,
           'used_trial' => $user->intro_subscription_used,
        ];
    }
}
