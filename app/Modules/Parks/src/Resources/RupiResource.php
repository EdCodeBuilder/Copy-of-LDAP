<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class RupiResource extends JsonResource
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
            'id'        =>  (int) isset( $this->id_Rupi ) ? (int) $this->id_Rupi : null,
            'name'      =>  isset( $this->Rupi ) ? toUpper($this->Rupi) : null,
            'park_id'   =>  (int) isset( $this->Id_Parque ) ? (int) $this->Id_Parque : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
