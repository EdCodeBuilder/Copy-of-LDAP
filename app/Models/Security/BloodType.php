<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_sim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupo_sanguineo';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_GrupoSanguineo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre_GrupoSanguineo'];

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
     * @return int
     */
    public function getIdAttribute()
    {
        return (int) $this->Id_GrupoSanguineo;
    }

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper($this->Nombre_GrupoSanguineo);
    }
}
