<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class Associated extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'pgsql_orfeo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'radi_asociados';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'radi_asociado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['radi_padre', 'fecha_crea'];
}
