<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'code'  =>  isset($this->code) ? (string) $this->code : null,
            'phone_code'  =>  isset($this->phone_code) ? (int) $this->phone_code : null,
            'sim_country_id'  =>  isset($this->sim_country_id) ? (int) $this->sim_country_id : null,
            'created_at'        => isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'        => isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
