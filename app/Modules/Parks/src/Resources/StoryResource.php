<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'id'        =>  (int) isset( $this->IdSubtitulo ) ? (int) $this->IdSubtitulo : null,
            'title'     =>  isset( $this->Subtitulo ) ? toUpper($this->Subtitulo) : null,
            'text'      =>  isset( $this->Parrafo ) ? $this->Parrafo : null,
            'park_id'   =>  (int) isset( $this->id_Parque ) ? (int) $this->id_Parque : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->isA(Roles::ROLE_ADMIN, Roles::ROLE_ASSIGNED),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
