<?php

namespace App\Modules\CitizenPortal\src\Traits;

use App\Modules\CitizenPortal\src\Models\CitizenNotification;
use Illuminate\Notifications\Notifiable;

trait CitizenNotifiable
{
    use Notifiable;

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(CitizenNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }
}
