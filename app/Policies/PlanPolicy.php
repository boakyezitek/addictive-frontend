<?php

namespace App\Policies;

use App\Services\NovaPermissions\NovaPermissionPolicy;

class PlanPolicy extends NovaPermissionPolicy
{

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'plans';
}
