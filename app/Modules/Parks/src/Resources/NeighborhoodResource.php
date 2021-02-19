<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodResource extends JsonResource
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
            'id'    =>  isset( $this->IdBarrio ) ? (int) $this->IdBarrio : null,
            'name'  =>  isset( $this->Barrio ) ? toUpper($this->Barrio) : null,
            'upz_code'   =>  isset( $this->CodUpz ) ? $this->CodUpz : null,
            'upz_id'=>  isset($this->upz->Id_Upz) ? (int) $this->upz->Id_Upz : null,
            'locality_id'=>  isset($this->upz->IdLocalidad) ? (int) $this->upz->IdLocalidad : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
