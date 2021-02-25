<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
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
            'id'        =>  (int) isset( $this->Id_Equipamento ) ? (int) $this->Id_Equipamento : null,
            'name'      =>  isset( $this->Equipamento ) ? toUpper($this->Equipamento) : null,
        ];
    }
}
