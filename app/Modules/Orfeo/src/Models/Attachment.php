<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
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
    protected $table = 'anexos';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'anex_codigo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'anex_radi_nume', 'anex_radi_fech', 'anex_estado', 'anex_numero'
    ];
}
