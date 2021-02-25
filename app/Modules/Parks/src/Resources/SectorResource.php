<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
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
            'id'        =>  isset( $this->i_pk_id ) ? (int) $this->i_pk_id : null,
            'park_id'   =>  isset( $this->i_fk_id_parque ) ? (int) $this->i_fk_id_parque : null,
            'sector'    =>  isset( $this->Sector ) ? $this->Sector : null,
            'coordinate'=>  isset( $this->coordenada ) ? $this->coordenada : null,
            'type'      =>  isset( $this->tipo ) ? (int) $this->tipo : null,
            'endowments'=> EndowmentResource::collection( $this->whenLoaded('endowments') ),
        ];
    }
}
