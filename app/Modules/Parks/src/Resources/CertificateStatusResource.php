<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Certified;
use App\Modules\Parks\src\Models\Enclosure;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $park = isset($this->Id_Parque) ? (int) $this->Id_Parque : null;
        return [
            'id'        =>  isset( $this->id_EstadoCertificado ) ? (int) $this->id_EstadoCertificado : null,
            'name'      =>  isset( $this->EstadoCertificado ) ? (string) $this->EstadoCertificado : null,
            'park_id'      =>  $park,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Certified::class, 'history'), Certified::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
