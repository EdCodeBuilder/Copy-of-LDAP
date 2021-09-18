<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Upz;
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
            'upz_code'   =>  isset( $this->CodUpz ) ? (string) $this->CodUpz : null,
            'neighborhood_code'   =>  isset( $this->CodBarrio ) ? (string) $this->CodBarrio : null,
            'upz_id'=>  isset($this->upz->Id_Upz) ? (int) $this->upz->Id_Upz : null,
            'locality_id'=>  isset($this->upz->IdLocalidad) ? (int) $this->upz->IdLocalidad : null,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Neighborhood::class, 'history'), Neighborhood::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
