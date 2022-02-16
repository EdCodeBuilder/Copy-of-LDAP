<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkService extends Model
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
      protected $table = 'parque_servicio';

      /**
       * The primary key for the model.
       *
       * @var string
       */
      protected $primaryKey = 'id_parque_servicio';

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
            'id_parque',
            'id_servicio',
      ];

      public function park()
      {
            return $this->belongsTo(ParkPse::class, 'id_parque', 'id_parque');
      }
      
      public function service()
      {
            return $this->belongsTo(ServiceOffered::class, 'id_servicio', 'id_servicio');
      }
}
