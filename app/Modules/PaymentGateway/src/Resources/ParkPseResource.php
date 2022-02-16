<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkPseResource extends JsonResource
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
                  'id'        =>  (int) isset($this->id_parque) ? (int) $this->id_parque : null,
                  'name'      =>  isset($this->nombre_parque) ? toUpper($this->nombre_parque) : null,
                  'code'      =>  isset($this->codigo_parque) ? toUpper($this->codigo_parque) : null,
                  'contact_name'      =>  isset($this->nombre_contacto) ? toUpper($this->nombre_contacto) : null,
                  'phones'      =>  isset($this->telefonos) ? toUpper($this->telefonos) : null,
                  'address'      =>  isset($this->direccion) ? toUpper($this->direccion) : null,
                  'email'      =>  isset($this->email) ? toUpper($this->email) : null
            ];
      }
}
