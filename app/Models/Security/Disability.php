<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Disability extends Model
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
    protected $table = 'discapacidad';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['discapacidad'];

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
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper($this->discapacidad);
    }
}
