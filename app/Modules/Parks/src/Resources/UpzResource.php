<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpzResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = isset( $this->Upz ) ? " - $this->Upz" : null;
        $code = isset( $this->cod_upz ) ? $this->cod_upz : null;
        return [
            'id'            =>  isset( $this->Id_Upz ) ? (int) $this->Id_Upz : null,
            'locality_id'   =>  isset( $this->IdLocalidad ) ? (int) $this->IdLocalidad : null,
            'name'          =>  isset( $this->Upz ) ? toUpper($this->Upz) : null,
            'upz_code'      =>  isset( $this->cod_upz ) ? $this->cod_upz : null,
            'composed_name' =>  toUpper("{$code}{$name}"),
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
