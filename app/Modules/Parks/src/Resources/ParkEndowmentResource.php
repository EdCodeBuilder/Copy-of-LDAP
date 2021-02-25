<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkEndowmentResource extends JsonResource
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
            $this->merge( new ParkFinderResource( $this->park ) ),
            'park_endowment_id' => isset( $this->Id ) ? (int) $this->Id : null,
            'endowment_id'      => isset( $this->Id_Dotacion ) ? (int) $this->Id_Dotacion : null,
            'endowment_description'      => isset( $this->Descripcion ) ? $this->Descripcion : null,
        ];
    }
}
