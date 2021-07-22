<?php

namespace App\Providers;

use App\Events\CharacterLocationChanged;
use App\Listeners\UpdateMap;
use App\Models\Character;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'App\Socialite\EveOnline\EveOnlineExtendSocialite@handle'
        ],
        CharacterLocationChanged::class => [
            [UpdateMap::class, 'handle']
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
