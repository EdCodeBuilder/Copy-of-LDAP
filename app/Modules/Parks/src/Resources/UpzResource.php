<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Upz;
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
            'upz_code'      =>  isset( $this->cod_upz ) ? (string) $this->cod_upz : null,
            'upz_type_id'      =>  isset( $this->Tipo ) ? (int) $this->Tipo : null,
            'upz_type'      =>  isset( $this->upz_type->name ) ? (string) $this->upz_type->name : null,
            'composed_name' =>  toUpper("{$code}{$name}"),
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Upz::class, 'history'), Upz::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
