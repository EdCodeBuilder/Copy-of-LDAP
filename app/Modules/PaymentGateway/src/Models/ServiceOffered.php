<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOffered extends Model
{
      use SoftDeletes;
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
      protected $table = 'servicio';

      /**
       * The primary key for the model.
       *
       * @var string
       */
      protected $primaryKey = 'id_servicio';

      /**
       * The attributes that should be mutated to dates.
       *
       * @var array
       */
      protected $dates = ['deleted_at'];

      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */
      protected $fillable = [
            'servicio_nombre',
            'codigo_servicio',
      ];

      public function parks()
      {
            return $this->belongsToMany(ParkPse::class, 'parque_servicio', 'id_servicio', 'id_parque')
                  ->withPivot('id_parque_servicio');
      }
}
