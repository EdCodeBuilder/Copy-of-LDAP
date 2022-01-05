<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;

class ParkPse extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_pse';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parque';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_parque';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_parque',
        'codigo_parque',
        'nombre_contacto',
        'telefonos',
        'direccion',
        'email',
    ];

    public function servicesOffered()
    {
        return $this->belongsToMany(ServiceOffered::class, 'parque_servicio','id_parque','id_servicio')
            ->withPivot('id_parque_servicio');
    }
}
