<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'id'        =>  isset( $this->Id_Actividad ) ? (int) $this->Id_Actividad : null,
            'name'      =>  isset( $this->Nombre_Actividad ) ? $this->Nombre_Actividad : null,
            'description'      =>  isset( $this->Descripcion ) ? $this->Descripcion : null,
            'module_id' =>  isset( $this->Id_Modulo ) ? (int) $this->Id_Modulo : null,
            'module'    => new ModuleResource( $this->whenLoaded('module') ),
            'permission'   => ActivityPermissionResource::collection( $this->whenLoaded('permission') )
        ];
    }
}
