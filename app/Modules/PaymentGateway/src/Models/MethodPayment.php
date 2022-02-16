<?php

namespace App\Modules\PaymentGateway\src\Models;

use Illuminate\Database\Eloquent\Model;

class MethodPayment extends Model
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
      protected $table = 'medio_pago';

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

            return $this->hasMany(Pago::class, 'medio_id', 'id');
      }
}
