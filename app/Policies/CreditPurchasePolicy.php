<?php

namespace App\Policies;

use App\Services\NovaPermissions\NovaPermissionPolicy;

class CreditPurchasePolicy extends NovaPermissionPolicy
{

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'credit_purchases';
}
