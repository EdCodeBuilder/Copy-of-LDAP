<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'id'        =>  (int) isset( $this->Id_Localidad ) ? (int) $this->Id_Localidad : null,
            'name'      =>  isset( $this->Localidad ) ? toUpper($this->Localidad) : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
