<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\SubscriptionOffer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionOfferPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create a subscription.
     *
     * @param  \App\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $admin)
    {
        $subscriptionOfferCount = SubscriptionOffer::count();
        return $subscriptionOfferCount < 1;
    }

    /**
     * Determine whether the admin can update the user.
     *
     *
     * @return mixed
     */
    public function update()
    {
        return true;
    }

    /**
     * Determine whether the admin can delete the user.
     *
     *
     * @return mixed
     */
    public function delete()
    {
        return true;
    }

    /**
     * Determine whether the admin can view the user.
     *
     *
     * @return mixed
     */
    public function view()
    {
        return true;
    }
}
