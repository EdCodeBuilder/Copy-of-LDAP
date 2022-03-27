<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EconomicUseParkResource extends JsonResource
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
            'id'            =>  isset( $this->Id ) ? (int) $this->Id : null,
            'park_id'       =>  isset( $this->IdParque ) ? (int) $this->IdParque : null,
            'activity_id'   =>  isset( $this->IdActividad ) ? (int) $this->IdActividad : null,
            'status_id'     =>  isset( $this->Estado ) ? (int) $this->Estado : null,
            'status'        =>  $this->status(),
            'activity'      =>  isset( $this->economic_use->Actividad ) ? toUpper($this->economic_use->Actividad) : null,
            'description'   =>  isset( $this->economic_use->Descripcion ) ? $this->economic_use->Descripcion : null,
            'manager'       =>  isset( $this->economic_use->GESTOR ) ? toUpper($this->economic_use->GESTOR) : null,
        ];
    }

    public function status()
    {
        switch (isset( $this->Estado ) ? (int) $this->Estado : null) {
            case 1:
                return 'SI';
            case 2:
                return 'NO';
            case 4:
                return 'ALGUNOS';
            case 3:
            default:
                return 'EN ESTUDIO';
        }
    }
}
