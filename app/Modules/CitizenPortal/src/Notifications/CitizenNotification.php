<?php

namespace App\Modules\CitizenPortal\src\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CitizenNotification extends DatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     * @return Model
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toArray($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => Str::title(str_replace("-", " ", Str::kebab(class_basename(get_class($notification))))),
            'notifiable_type' => "users",
            'data' => $data,
            'read_at' => null,
        ]);
    }
}
