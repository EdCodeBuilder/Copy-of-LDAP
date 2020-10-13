<?php

namespace App\Listeners\Security;

use Adldap\Laravel\Events\AuthenticatedWithCredentials;
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
     * @param DiscoveredWithCredentials $event
     * @return void
     * @throws PasswordExpiredException
     */
    public function handle(DiscoveredWithCredentials $event)
    {
        Log::info('Entrada al Listener de contraseñas');
        if ((int) $event->user->getPasswordLastSet() !== 0) {
            throw new PasswordExpiredException('Password Expired');
        }
        if ((int) $event->user->getFirstAttribute('useraccountcontrol') !== 514) {
            throw new PasswordExpiredException('Cuenta inactiva');
        }
    }
}
