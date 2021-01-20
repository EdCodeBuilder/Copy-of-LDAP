<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class Certified extends Model
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
    protected $table = 'estadocertificado';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_EstadoCertificado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'EstadoCertificado' ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
