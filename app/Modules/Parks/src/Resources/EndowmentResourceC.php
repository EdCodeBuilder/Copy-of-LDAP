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
            'Id_Dotacion'        	=>  (int) isset( $this->Id_Dotacion ) ? (int) $this->Id_Dotacion : null,
            'Dotacion'      	=>  isset( $this->Dotacion ) ? toUpper($this->Dotacion) : null,
	     'Id_Equipamento'      	=>  (int) isset( $this->Id_Equipamento ) ? (int) $this->Id_Equipamento : null,
        ];
    }
}
