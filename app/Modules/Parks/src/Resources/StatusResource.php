<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'id'        =>  isset( $this->Id_Estado ) ? (int) $this->Id_Estado : null,
            'name'      =>  isset( $this->Estado ) ? toUpper($this->Estado) : null,
        ];
    }
}
