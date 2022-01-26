<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicePseResource extends JsonResource
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
            'id'        =>  (int) isset($this->id_servicio) ? (int) $this->id_servicio : null,
            'name_service'      =>  isset($this->servicio_nombre) ? toUpper($this->servicio_nombre) : null,
            'code_service'      =>  isset($this->codigo_servicio) ? toUpper($this->codigo_servicio) : null,
        ];
    }
}
