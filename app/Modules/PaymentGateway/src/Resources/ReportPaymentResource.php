<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportPaymentResource extends JsonResource
{
      /**
       * Transform the resource into an array.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return array
       */
      public function toArray($request)
      {
            return [
                  'id'        =>  isset($this->id) ? (int) $this->id : '_',
                  'date_payment'      =>  isset($this->fecha_pago) ? $this->fecha_pago : '_',
                  'amount'      =>  isset($this->total) ? $this->total : '_',
                  'park_code'      =>  isset($this->codigo_parque) ? $this->codigo_parque : '_',
                  'name_park'      =>  isset($this->nombre_parque) ? $this->nombre_parque : '_',
                  'name_service'      =>  isset($this->servicio_nombre) ? $this->servicio_nombre : '_',
                  'document' =>  isset($this->identificacion) ? $this->identificacion : '_',
                  'email' =>  isset($this->email) ? $this->email : '_',
                  'name' =>  isset($this->nombre) ? $this->nombre : '_',
                  'last_name' =>  isset($this->apellido) ? $this->apellido : '_',
                  'phone' =>  isset($this->telefono) ? $this->telefono : '_',
                  'concept' => isset($this->concepto) ? $this->concepto : '_',
            ];
      }
}
