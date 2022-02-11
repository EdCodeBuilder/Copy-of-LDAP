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
                  'id' => $this->id_parque_servicio ? $this->id_parque_servicio : '',
                  'park' => $this->whenLoaded('park')['nombre_parque'] ? $this->whenLoaded('park')['nombre_parque'] : '',
                  'service' => $this->whenLoaded('service')['servicio_nombre'] ? $this->whenLoaded('service')['servicio_nombre'] : '',
                  'parkId' => $this->whenLoaded('park')['id_parque'] ? $this->whenLoaded('park')['id_parque'] : '',
                  'serviceId' => $this->whenLoaded('service')['id_servicio'] ? $this->whenLoaded('service')['id_servicio'] : '',
            ];
      }
}
