<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Chapter;
use App\Models\Bookmark;
use App\Models\AudioBook;
use App\Policies\AdminPolicy;
use Laravel\Passport\Passport;
use App\Policies\ChapterPolicy;
use App\Policies\BookmarkPolicy;
use App\Policies\AudioBookPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Admin::class => AdminPolicy::class,
        Bookmark::class => BookmarkPolicy::class,
        Chapter::class => ChapterPolicy::class,
        AudioBook::class => AudioBookPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::before(function ($admin, $ability) {
            if($admin == 'App\Models\Admin'){
                return $admin->hasRole('Super Admin') ? true : null;
            }
        });

        Gate::after(function ($admin, $ability) {
            if($admin == 'App\Models\Admin'){
                return $admin->hasRole('Super Admin'); // note this returns boolean
            }
        });
    }
}
