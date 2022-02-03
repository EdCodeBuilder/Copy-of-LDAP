<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkServiceResource extends JsonResource
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
                  'id' => $this->id_parque_servicio ? $this->id_parque_servicio : '_',
                  'park' => $this->whenLoaded('park')['nombre_parque'] ? $this->whenLoaded('park')['nombre_parque'] : '_',
                  'service' => $this->whenLoaded('service')['servicio_nombre'] ? $this->whenLoaded('service')['servicio_nombre'] : '_'
            ];
      }
}
