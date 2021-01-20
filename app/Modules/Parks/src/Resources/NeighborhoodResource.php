<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'    =>  isset( $this->IdBarrio ) ? (int) $this->IdBarrio : null,
            'name'  =>  isset( $this->Barrio ) ? toUpper($this->Barrio) : null,
            'upz'   =>  isset( $this->CodUpz ) ? $this->CodUpz : null,
        ];
    }
}
