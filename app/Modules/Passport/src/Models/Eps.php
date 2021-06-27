<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_eps';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'i_pk_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vc_nombre', 'i_estado'];

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
     * Get the eps's name.
     *
     * @return string
     */
    public function getIdAttribute()
    {
        return isset($this->i_pk_id) ? (int) $this->i_pk_id : null;
    }

    /**
     * Get the eps's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( "{$this->vc_nombre}" );
    }

    /**
     * Get the eps's status.
     *
     * @return bool
     */
    public function getStatusAttribute()
    {
        return isset($this->i_estado) ? (bool) $this->i_estado : null;
    }
}
