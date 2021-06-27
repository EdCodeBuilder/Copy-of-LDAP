<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'id'    =>  isset($this->id) ?  (int) $this->id : null,
            'name'  =>  isset($this->name) ? (string) $this->name : null,
            'country_id'  =>  isset($this->country_id) ? (int) $this->country_id : null,
            'sim_state_id'  =>  isset($this->sim_state_id) ? (int) $this->sim_state_id : null,
            'created_at'  => isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'  => isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
