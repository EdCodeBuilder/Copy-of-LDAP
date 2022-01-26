<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransaccionsPseResource extends JsonResource
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
                  'document'        =>  (int) isset($this->identificacion) ? (int) $this->identificacion : null,
                  'code_pay'      =>  isset($this->codigo_pago) ? $this->codigo_pago : null,
                  'transaccion_id'      =>  isset($this->id_transaccion_pse) ? $this->id_transaccion_pse : null,
                  'email'      =>  isset($this->email) ? $this->email : null,
                  'full_name'      =>  isset($this->nombre) ? $this->nombre . ' ' . $this->apellido : null,
                  'phone'      =>  isset($this->telefono) ? $this->telefono : null,
                  'status'      =>  isset($this->estado) ? $this->estado : null,
                  'status_bank'      =>  isset($this->estado_banco) ? $this->estado_banco : null,
                  'concept'      =>  isset($this->concepto) ? $this->concepto : null,
                  'total_pay'      =>  isset($this->total) ? $this->total : null,
                  'date'      =>  isset($this->updated_at) ? $this->updated_at->toDateTimeString(): null,

            ];
      }
}
