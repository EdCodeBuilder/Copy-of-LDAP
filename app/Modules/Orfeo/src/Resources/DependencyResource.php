<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DependencyResource extends JsonResource
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
            'id'    =>  isset( $this->depe_codi ) ? (int) $this->depe_codi : null,
            'name'  =>  isset( $this->name ) ? $this->name : null,
        ];
    }
}