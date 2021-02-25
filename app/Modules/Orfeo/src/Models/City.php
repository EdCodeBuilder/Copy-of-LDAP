<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
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
    protected $table = 'municipio';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'muni_codi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['muni_nomb'];

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
        return toUpper( $this->muni_nomb );
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *  Filed belongs to state
     *
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'dpto_codi');
    }
}
