<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Origin;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginResource extends JsonResource
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
            'id'        =>  isset( $this->IdParque ) ? (int) $this->IdParque : null,
            'park_id'        => isset( $this->IdParque ) ? (int) $this->IdParque : null,
            'paragraph_1'      =>  isset( $this->Parrafo1 ) ? (string) $this->Parrafo1 : null,
            'paragraph_2'      =>  isset( $this->Parrafo2 ) ? (string) $this->Parrafo2 : null,
            'image_1'          => isset($this->imagen1) ? (string) $this->imagen1 : null,
            'image_2'          => isset($this->imagen2) ? (string) $this->imagen2 : null,
            'image_3'          => isset($this->imagen3) ? (string) $this->imagen3 : null,
            'image_4'          => isset($this->imagen4) ? (string) $this->imagen4 : null,
            'image_5'          => isset($this->imagen5) ? (string) $this->imagen5 : null,
            'image_6'          => isset($this->imagen6) ? (string) $this->imagen6 : null,
            'images'    => isset( $this->images ) ? (array) $this->images : [],
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Origin::class, 'history'), Origin::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
