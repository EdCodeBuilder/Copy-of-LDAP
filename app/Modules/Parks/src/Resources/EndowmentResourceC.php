<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EndowmentResourceC extends JsonResource
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
            'id'        	=>  isset( $this->Id_Dotacion ) ? (int) $this->Id_Dotacion : null,
            'name'      	=>  isset( $this->Dotacion ) ? toUpper($this->Dotacion) : null,
	        'equipment_id'      	=>  isset( $this->Id_Equipamento ) ? (int) $this->Id_Equipamento : null,
        ];
    }
}
