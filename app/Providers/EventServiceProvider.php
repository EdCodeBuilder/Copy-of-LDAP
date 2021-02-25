<?php

namespace App\Providers;

use Adldap\Laravel\Events\AuthenticatedWithCredentials;
use Adldap\Laravel\Events\Authenticating;
use Adldap\Laravel\Events\DiscoveredWithCredentials;
use App\Listeners\Security\PasswordExpiredListener;
use App\Listeners\Security\PruneOldTokens;
use App\Listeners\Security\RevokeOldTokens;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;

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
        AccessTokenCreated::class => [
            RevokeOldTokens::class,
        ],
        RefreshTokenCreated::class => [
            PruneOldTokens::class,
        ],
        Authenticating::class => [
            PasswordExpiredListener::class,
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

        //
    }
}
