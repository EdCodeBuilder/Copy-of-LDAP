<?php

namespace App\Http\Resources\GlobalData;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeResource extends JsonResource
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
            'id'            =>  isset($this->id) ? (int) $this->id : null,
            'name'          =>  isset($this->name) ? $this->name : null,
            'description'   =>  isset($this->description) ? $this->description : null,
            'combined'      =>  "{$this->name} - {$this->description}",
        ];
    }
}
