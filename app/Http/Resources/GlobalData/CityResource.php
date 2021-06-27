<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'state_id'  =>  isset($this->state_id) ? (int) $this->state_id : null,
            'sim_city_id'  =>  isset($this->sim_city_id) ? (int) $this->sim_city_id : null,
            'created_at'  => isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'  => isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
