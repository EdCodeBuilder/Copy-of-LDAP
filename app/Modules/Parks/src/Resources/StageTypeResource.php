<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class StageTypeResource extends JsonResource
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
            'id'        =>  isset( $this->id ) ? (int) $this->id : null,
            'name'      =>  isset( $this->tipo ) ? toUpper($this->tipo) : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
