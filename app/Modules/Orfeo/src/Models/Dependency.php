<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
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
    protected $table = 'dependencia';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'depe_codi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['depe_nomb'];

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
        return toUpper( $this->depe_nomb );
    }
}
