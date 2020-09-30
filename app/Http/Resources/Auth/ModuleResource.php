<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'id'      => isset( $this->id ) ? (int) $this->id : null,
            'name'      => isset( $this->name ) ? $this->name : null,
            'area'      => isset( $this->area ) ? $this->area : null,
            'redirect'      => isset( $this->redirect ) ? $this->redirect : null,
            'image'     => isset( $this->image ) ? $this->image : null,
            'status'        => isset( $this->status ) ? (bool) $this->status : null,
            'missionary'        => isset( $this->missionary ) ? (bool) $this->missionary : null,
            'compatible'        => isset( $this->compatible ) ? (bool) $this->compatible : null,
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
