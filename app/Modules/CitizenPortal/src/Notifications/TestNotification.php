<?php


namespace App\Modules\CitizenPortal\src\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CitizenNotification::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'         => "Hola",
            'subject'       => "Probando",
            'user'          => "Usuario",
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'url'           => [
                'name'      =>  'Notifications',
                'params'    =>  [ 'id'  => 1 ]
            ]
        ];
    }
}
