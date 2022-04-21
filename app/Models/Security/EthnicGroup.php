<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class EthnicGroup extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_sim";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'etnia';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Etnia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre_Etnia'];

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
        return (int) $this->Id_Etnia;
    }

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper($this->Nombre_Etnia);
    }
}
