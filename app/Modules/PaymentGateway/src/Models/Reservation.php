<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_pse';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reserva_sintetica';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
