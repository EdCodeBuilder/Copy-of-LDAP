<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScaleResource extends JsonResource
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
            'id'        =>  (int) isset( $this->Id_Tipo ) ? (int) $this->Id_Tipo : null,
            'name'      =>  isset( $this->Tipo ) ? toUpper($this->Tipo) : null,
        ];
    }
}
