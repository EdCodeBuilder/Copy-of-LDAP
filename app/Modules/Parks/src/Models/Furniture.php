<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
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
    protected $table = 'mobiliario';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdMobiliario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Mobiliario',
        'Descripcion'
    ];

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

    public function getIdAttribute()
    {
        return (int) $this->IdMobiliario;
    }

    public function getNameAttribute()
    {
        return toUpper( $this->Mobiliario );
    }

    public function parks()
    {
        return $this->belongsToMany(Park::class, 'parquemobiliario','idMobiliario','idParque')
            ->withPivot('Material', 'Bueno', 'Malo', 'Regular', 'Total', 'Descrpicion', 'Imagen', 'Publico');
    }

}
