<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Story;
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
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Story::class, 'history'), Story::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
        ];
    }
}
