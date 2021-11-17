<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  isset( $this->id ) ? (int) $this->id : null,
            'version'       =>  isset( $this->version ) ? (int) $this->version : null,
            'file_id'       =>  isset( $this->idArchivo ) ? (int) $this->idArchivo : null,
            'file'          =>  isset( $this->file->nombreArchivo ) ? toUpper($this->file->nombreArchivo) : null,
            'url'           =>  isset( $this->file->nombreArchivo ) ? "https://sim1.idrd.gov.co/SIM/Parques/PlanesEmergencia/{$this->file->nombreArchivo}" : null,
            'description'   =>  isset( $this->file->descripcionArchivo ) ? toUpper($this->file->descripcionArchivo) : null,
            'category'   =>  isset( $this->file->category->categoria ) ? toUpper($this->file->category->categoria) : null,
            'order'         =>  isset( $this->file->orden ) ? (int) $this->file->orden : null,
            'park_id'       =>  isset( $this->idParque ) ? (int) $this->idParque : null,
            'created_at'    =>  isset($this->file->fecha) ? $this->file->fecha->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->Fecha_Version) ? $this->Fecha_Version->format('Y-m-d H:i:s'): null,
        ];
    }
}
