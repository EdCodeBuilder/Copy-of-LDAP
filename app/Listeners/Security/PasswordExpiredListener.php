<?php

namespace App\Listeners\Security;

use Adldap\Laravel\Events\DiscoveredWithCredentials;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  object  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(DiscoveredWithCredentials $event)
    {
        if ((int) $event->user->getPasswordLastSet() !== 0) {
            return response()->json([
                'message' =>  'Password Bloqueado',
                'details' => '',
                'code'  =>  Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
