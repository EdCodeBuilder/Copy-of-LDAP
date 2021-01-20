<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsLocationResource extends JsonResource
{
    public $additional = [
        'label' => 'Mapa de Bogotá',
        'viewBox' => '0 0 920 400',
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        =>  (int) isset( $this->Id_Localidad ) ? (string) $this->Id_Localidad : null,
            'name'      =>  isset( $this->Localidad ) ? toUpper($this->Localidad) : null,
            'parksCount'   =>  isset( $this->parks_count ) ? (int) $this->parks_count : 0,
        ];
    }
}
