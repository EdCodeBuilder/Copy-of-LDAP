<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class InformedResource extends JsonResource
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
            'id'                =>  isset( $this->radi_nume_radi ) ? (int) $this->radi_nume_radi : null,
            'dependency_id'     =>  isset( $this->depe_codi ) ? (int) $this->depe_codi : null,
            'dependency'        => isset( $this->dependency->name ) ? $this->dependency->name : null,
            'user_id'           =>  isset( $this->usua_codi ) ? (int) $this->usua_codi : null,
            'user'              => isset( $this->user->name ) ? $this->user->name : null,
            'user_document'     => isset( $this->user->document ) ? (int) $this->user->document : null,
            'description'       => isset( $this->info_desc ) ? toUpper( $this->info_desc ) : null,
            'read'              => isset( $this->info_leido ) ? (bool) $this->info_leido : null,
            'created_at'        =>  isset( $this->info_fech ) ? $this->info_fech : null,
        ];
    }
}