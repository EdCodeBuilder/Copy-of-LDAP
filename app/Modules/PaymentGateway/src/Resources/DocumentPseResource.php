<?php

namespace App\Modules\PaymentGateway\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentPseResource extends JsonResource
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
            'id'        =>  (int) isset($this->id) ? (int) $this->id : null,
            'name'      =>  isset($this->name) ? $this->name : null,
            'description'      =>  isset($this->description) ? $this->description : null,
        ];
    }
}
