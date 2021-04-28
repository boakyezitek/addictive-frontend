<?php

namespace App\Policies;

use App\Services\NovaPermissions\NovaPermissionPolicy;

class AdminPolicy extends NovaPermissionPolicy
{
    public static $key = 'admins';
}
