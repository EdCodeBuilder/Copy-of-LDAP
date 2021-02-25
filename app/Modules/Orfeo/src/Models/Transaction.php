<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
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
    protected $table = 'sgd_ttr_transaccion';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sgd_ttr_codigo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sgd_ttr_descript'
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( $this->sgd_ttr_descrip );
    }
}
