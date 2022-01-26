<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
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
      protected $table = 'pago_pse';

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
      protected $fillable = [
            'id',
            'id_parque',
            'id_servicio',
            'identificacion',
            'tipo_identificacion',
            'codigo_pago',
            'email',
            'nombre',
            'apellido',
            'telefono',
            'estado',
            'concepto',
            'total',
            'iva',
            'permiso',
            'tipo_permiso',
            'id_reserva',
      ];
}
