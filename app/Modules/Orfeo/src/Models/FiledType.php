<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiledType extends Model
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
    protected $table = 'sgd_trad_tiporad';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sgd_trad_codigo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sgd_trad_descr'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the description name.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return toUpper($this->sgd_trad_descr);
    }

    /**
     * Get the description name.
     *
     * @return integer
     */
    public function getIdAttribute()
    {
        return (int) $this->sgd_trad_codigo;
    }

}
