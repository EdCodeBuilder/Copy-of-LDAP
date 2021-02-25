<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends Model
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
    protected $table = 'sgd_def_paises';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_pais';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_pais'];

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
        return toUpper( $this->nombre_pais );
    }
}
