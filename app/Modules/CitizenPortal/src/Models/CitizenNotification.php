<?php

namespace App\Modules\CitizenPortal\src\Models;


use Illuminate\Notifications\DatabaseNotification;

class CitizenNotification extends DatabaseNotification
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_citizen_portal";


    /**
     * @param $value
     * @return void
     */
    public function setNotifiableTypeAttribute($value) {
        $this->attributes['notifiable_type'] = "users";
    }
}
