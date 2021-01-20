<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class EconomicUse extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_parks';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ActividadesAprovechamiento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Id',
        'Actividad',
        'Descripcion',
        'GESTOR',
    ];
}
