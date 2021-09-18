<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Vocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VocationResource extends JsonResource
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
            'name'          =>  isset( $this->vocacion ) ? toUpper($this->vocacion) : null,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Vocation::class, 'history'), Vocation::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
