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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
    public function getNameAttribute()
    {
        return toUpper($this->Barrio);
    }

    /**
     * Set name in uppercase
     *
     * @param $value
     * @return void
     */
    public function setBarrioAttribute($value)
    {
        $this->attributes['Barrio'] = toUpper($value);
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
    public function upz()
    {
        return $this->belongsTo(Upz::class, 'CodUpz', 'cod_upz');
    }
}
