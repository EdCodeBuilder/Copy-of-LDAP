<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
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
            'id'        =>  isset( $this->id ) ? (int) $this->id : null,
            'user'      =>  isset( $this->user->full_name ) ? toUpper($this->user->full_name) : null,
            'event'     =>  isset( $this->event ) ? __("validation.events.{$this->event}") : null,
            'type'      =>  isset( $this->auditable_type ) ? $this->auditable_type : null,
            'type_id'   =>  isset( $this->auditable_id ) ? (int) $this->auditable_id : null,
            'old_values'=>  isset( $this->old_values ) ? $this->old_values : null,
            'new_values'=>  isset( $this->new_values ) ? $this->new_values : null,
            'url'       =>  isset( $this->url ) ? $this->url : null,
            'ip'        =>  isset( $this->ip_address ) ? $this->ip_address : null,
            'user_agent'=>  isset( $this->user_agent ) ? $this->user_agent : null,
            'tags'      =>  isset( $this->tags ) ? $this->tags : null,
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
