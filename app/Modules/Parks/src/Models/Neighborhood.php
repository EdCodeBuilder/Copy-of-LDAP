<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Neighborhood extends Model
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
    protected $table = 'Barrios';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdBarrio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Barrio', 'CodUpz'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return toUpper($this->Barrio);
    }

    /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     * A Neighborhood Belongs To UPZ
     *
     * @return BelongsTo
     */
    public function upz(): BelongsTo
    {
        return $this->belongsTo(Upz::class, 'CodUpz', 'cod_upz');
    }
}
