<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Events\AudioChapterAdded;
use App\Events\AddMultipleChapters;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Observers\SubscriptionObserver;
use App\Listeners\Chapters\UpdateDuration;
use App\Listeners\Chapters\MultipleChaptersAdded;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AudioChapterAdded::class => [
            UpdateDuration::class,
        ],

        AddMultipleChapters::class => [
            MultipleChaptersAdded::class,
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Subscription::observe(SubscriptionObserver::class);
    }
}
