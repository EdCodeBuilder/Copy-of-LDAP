<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
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
      protected $table = 'estado_pse';

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
      ];


      public function payments()
      {
            return $this->hasMany(Pago::class, 'estado_id', 'id');
      }
}
