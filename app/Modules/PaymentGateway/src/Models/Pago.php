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
      protected $fillable = [];

      public function state()
      {
            return $this->belongsTo(Status::class, 'estado_id', 'id')->select(['id', 'estado_paymentez', 'descripcion']);
      }

      public function method()
      {
            return $this->belongsTo(MethodPayment::class, 'medio_id', 'id')->select(['id', 'Nombre']);
      }
      public function service()
      {
            return $this->belongsTo(ServiceOffered::class, 'servicio_id', 'id_servicio')->select(['id_servicio', 'servicio_nombre', 'codigo_servicio']);
      }
      
      public function park()
      {
            return $this->belongsTo(ParkPse::class, 'parque_id', 'id_parque')->select(['id_parque', 'nombre_parque', 'codigo_parque']);
      }
}
