<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class ActivityAccess extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_sim";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividad_acceso';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "Id_Persona";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "Id_Actividad",
        "Estado",
        "TimeStamp",
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['TimeStamp'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */
}
