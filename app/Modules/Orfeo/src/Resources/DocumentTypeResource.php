<?php


namespace App\Modules\Orfeo\src\Resources;


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
            'id'    =>  isset( $this->sgd_tpr_codigo ) ? (int) $this->sgd_tpr_codigo : null,
            'name'  =>  isset( $this->name ) ? $this->name : null,
            'business_days'  =>  isset( $this->business_days ) ? (int) $this->business_days : null,
        ];
    }
}