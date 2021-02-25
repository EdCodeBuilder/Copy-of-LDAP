<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
            'name'  =>  isset($this->name) ? $this->name : null,
            'subdirectorate_id'    =>  isset($this->subdirectorate_id) ?  (int) $this->subdirectorate_id : null,
            'subdirectorate'    =>  isset($this->subdirectorates->name) ?  $this->subdirectorates->name : null,
            'created_at' => isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
