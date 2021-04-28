<?php

namespace App\Providers;

use App\Nova\Plan;
use App\Nova\User;
use App\Nova\Bonus;
use App\Nova\Event;
use App\Nova\Order;
use App\Nova\Author;
use App\Nova\Credit;
use App\Nova\Reader;
use App\Models\Admin;
use App\Nova\AppUser;
use App\Nova\Chapter;
use App\Nova\Keyword;
use App\Nova\Category;
use App\Nova\Language;
use Laravel\Nova\Nova;
use App\Nova\AudioBook;
use App\Nova\Parameter;
use App\Nova\CreditPack;
use App\Nova\HomeSection;
use App\Nova\Transaction;
use App\Nova\Subscription;
use App\Nova\CreditPurchase;
use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\NewUsers;
use App\Nova\SubscriptionOffer;
use App\Nova\LoginScreenPicture;
use App\Nova\Metrics\UsersPerDay;
use App\Nova\SubscriptionSection;
use Illuminate\Support\Facades\Gate;
use App\Nova\Dashboards\UserInsights;
use App\Nova\Metrics\NewSubscriptions;
use App\Nova\Metrics\NewCreditPurchases;
use Laravel\Nova\NovaApplicationServiceProvider;
use DigitalCreative\CollapsibleResourceManager\Resources\Group;
use DigitalCreative\CollapsibleResourceManager\Resources\ExternalLink;
use DigitalCreative\CollapsibleResourceManager\Resources\InternalLink;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function (Admin $admin) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NewUsers,
            new NewCreditPurchases,
            new NewSubscriptions,
            (new UsersPerDay)->width('full')
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            //new UserInsights
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new CollapsibleResourceManager([
                'navigation' => [
                    'disable_default_resource_manager' => true, // default
                    'remember_menu_state' => true, // default
                    TopLevelResource::make([
                        'label' => __('Contenu'),
                        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                        'resources' => [
                            AudioBook::class,
                            Author::class,
                            Reader::class,
                            Bonus::class,
                            //Chapter::class,
                            Category::class,
                            Language::class,
                            HomeSection::class,
                            SubscriptionSection::class,
                            SubscriptionOffer::class,
                            LoginScreenPicture::class,
                            Parameter::class
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Crédits'),
                        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                        'resources' => [
                            Plan::class,
                            Subscription::class,
                            Credit::class,
                            // CreditPack::class,
                            // CreditPurchase::class
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Historique'),
                        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                        'resources' => [
                            // Transaction::class,
                            // Order::class,
                            Event::class
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Utilisateurs'),
                        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                        'resources' => [
                            User::class,
                            AppUser::class
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Raccourcis'),
                        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>',
                        'resources' => [
                            ExternalLink::make([
                                'label' => 'ZenDesk',
                                'badge' => null,
                                'icon' => '<svg></svg>',
                                'target' => '_blank',
                                'url' => 'https://google.com'
                            ]),
                            ExternalLink::make([
                                'label' => 'iOS',
                                'badge' => null,
                                'icon' => '<svg></svg>',
                                'target' => '_blank',
                                'url' => 'https://google.com'
                            ]),
                            ExternalLink::make([
                                'label' => 'Android',
                                'badge' => null,
                                'icon' => '<svg></svg>',
                                'target' => '_blank',
                                'url' => 'https://google.com'
                            ]),
                            ExternalLink::make([
                                'label' => 'Firebase',
                                'badge' => null,
                                'icon' => '<svg></svg>',
                                'target' => '_blank',
                                'url' => 'https://google.com'
                            ])

                        ]
                    ]),
                ]
            ]),
            (new \Eminiarts\NovaPermissions\NovaPermissions())->canSee(function ($request) {
                return $request->user()->isSuperAdmin();
            }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
