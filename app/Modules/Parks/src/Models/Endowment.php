<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class Endowment extends Model
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
    protected $table = 'dotacion';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Dotacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Dotacion',
        'Id_Equipamento'
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
        return (int) $this->Id_Dotacion;
    }

    public function getNameAttribute()
    {
        return toUpper( $this->Dotacion );
    }

    public function parks()
    {
        return $this->belongsToMany(Park::class, 'parquedotacion','Id_Dotacion','Id_Parque')
            ->withPivot('Num_Dotacion', 'Estado', 'Material', 'iluminacion', 'Aprovechamientoeconomico', 'Area', 'MaterialPiso', 'Cerramiento', 'Camerino', 'Luz', 'Agua', 'Gas', 'Capacidad', 'Carril', 'Bano', 'BateriaSanitaria', 'Descripcion', 'Diag_Mantenimiento', 'Diag_Construcciones', 'Posicionamiento', 'Destinacion', 'Imagen', 'Fecha', 'TipoCerramiento', 'AlturaCerramiento', 'Largo', 'Ancho', 'Cubierto', 'Dunt', 'B_Masculino', 'B_Femenino', 'B_Discapacitado', 'C_Vehicular', 'C_BiciParqueadero', 'Publico');
    }

    public function material()
    {
        return $this->hasOne( Material::class, 'MaterialPiso', 'IdMaterial');
    }


    public function equipment()
    {
        return $this->hasOne(Equipment::class, 'Id_Equipamento', 'Id_Equipamento');
    }

}
