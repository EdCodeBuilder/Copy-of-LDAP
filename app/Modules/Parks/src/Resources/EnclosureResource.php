<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnclosureResource extends JsonResource
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
            'id'        =>  (int) isset( $this->Id_Cerramiento ) ? (int) $this->Id_Cerramiento : null,
            'name'      =>  isset( $this->Cerramiento ) ? $this->Cerramiento : null,
            'audit'     =>  $this->audits()->with('user:id,name,surname')->latest()->get()
        ];
    }
}
