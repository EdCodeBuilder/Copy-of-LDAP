<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'    =>  isset( $this->id ) ? (int) $this->id : null,
            'name'  =>  isset( $this->name ) ? $this->name : null,
            'document'  =>  isset( $this->document ) ? (int) $this->document : null,
            'email'  =>  isset( $this->email ) ? $this->email : null,
        ];
    }
}