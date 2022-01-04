<?php

namespace App\Modules\Payroll\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Person extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_sim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persona';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Persona';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Primer_Apellido',
        'Segundo_Apellido',
        'Primer_Nombre',
        'Segundo_Nombre',
        'Fecha_Nacimiento',
        'Cedula',
        'Id_TipoDocumento',
    ];
}
