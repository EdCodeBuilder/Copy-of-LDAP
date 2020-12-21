<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityPermissionResource extends JsonResource
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
            'user_id'       =>  isset( $this->Id_Persona ) ? (int) $this->Id_Persona : null,
            'activity_id'   =>  isset( $this->Id_Actividad ) ? (int) $this->Id_Actividad : null,
            'status'        =>  isset( $this->Estado ) ? (bool) $this->Estado : null,
            'status_int'    =>  isset( $this->Estado ) ? (int) $this->Estado : null,
            'created_at'    =>  isset( $this->TimeStamp ) ? $this->TimeStamp->format('Y-m-d H:i:s') : null,
        ];
    }
}
