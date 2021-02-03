<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
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
    protected $table = 'localidad';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Localidad';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Localidad'];

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
        return toUpper($this->Localidad);
    }

   /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     * An scale has many parks
     *
     * @return HasMany
     */
    public function parks()
    {
        return $this->hasMany(Park::class, 'Id_Localidad', 'Id_Localidad');
    }

    /**
     * An scale has many upz
     *
     * @return HasMany
     */
    public function upz()
    {
        return $this->hasMany(Upz::class, 'IdLocalidad', 'Id_Localidad');
    }
}
