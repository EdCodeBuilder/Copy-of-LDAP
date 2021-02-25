<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AbilityResource extends JsonResource
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
            'id'        => isset( $this->id ) ? (int) $this->id : null,
            'name'      => isset( $this->name ) ? $this->name : null,
            'title'      => isset( $this->title ) ? $this->title : null,
            'entity_id'      => isset( $this->entity_id ) ? (int) $this->entity_id : null,
            'entity_type'      => isset( $this->entity_type ) ? $this->entity_type : null,
            'only_owned'      => isset( $this->only_owned ) ? (bool) $this->only_owned : null,
            'options'      => isset( $this->options ) ? $this->options : null,
            'scope'      => isset( $this->scope ) ? (int) $this->scope : null,
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
