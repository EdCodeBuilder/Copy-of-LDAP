<?php

namespace App\Listeners\Security;

use Adldap\Laravel\Events\AuthenticatedWithCredentials;
use Adldap\Laravel\Events\Authenticating;
use Adldap\Laravel\Events\DiscoveredWithCredentials;
use App\Exceptions\PasswordExpiredException;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PasswordExpiredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Authenticating $event
     * @return void
     * @throws PasswordExpiredException
     */
    public function handle(Authenticating $event)
    {
        Log::info('Starting Authentication Listener...');
        Log::info('Username: '.$event->user->getFirstAttribute('samaccountname'));
        Log::info('Password last set: '.$event->user->getPasswordLastSet());
        Log::info('Account Control: '.$event->user->getFirstAttribute('useraccountcontrol'));
        Log::info('Finishing Authentication Listener');
        if ((int) $event->user->getPasswordLastSet() === 0) {
            throw new PasswordExpiredException(trans('passwords.expired'));
        }
        if ((int) $event->user->getFirstAttribute('useraccountcontrol') === 514) {
            throw new PasswordExpiredException(trans('passwords.inactive'));
        }
    }
}
