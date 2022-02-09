<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusPseResource extends JsonResource
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
                  'id' => $this->id ? $this->id : '_',
                  'document' => $this->identificacion ? $this->identificacion : '_',
                  'transaccion_id_pse' => $this->id_transaccion_pse ? $this->id_transaccion_pse : '_',
                  'name' => $this->nombre ? $this->nombre : '_',
                  'last_name' => $this->apellido ? $this->apellido : '_',
                  'phone' => $this->telefono ? $this->telefono : '_',
                  'status_bank' => $this->estado_banco ? $this->estado_banco : '_',
                  'concept' => $this->concepto ? $this->concepto : '_',
                  'currently' => $this->moneda ? $this->moneda : '_',
                  'amount' => $this->total ? $this->total : '_',
                  'tax' => $this->iva ? $this->iva : '_',
                  'date_payment' => $this->fecha_pago ? $this->fecha_pago : '_',
                  'user_id_pse' => $this->user_id_pse ? $this->user_id_pse : '_',
                  'code_payment' => $this->codigo_pago ? $this->codigo_pago : '_',
                  'email' => $this->email ? $this->email :'_',
                  'status' => $this->whenLoaded('state'),
                  'method' => $this->whenLoaded('method')
            ];
      }
}
