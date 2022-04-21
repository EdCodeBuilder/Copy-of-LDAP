<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class SexualOrientationResource extends JsonResource
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
            'name'  => toUpper($this->name ?? null),
        ];
    }
}
