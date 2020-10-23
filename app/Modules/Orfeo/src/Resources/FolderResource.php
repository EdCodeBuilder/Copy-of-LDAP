<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
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
            'id'                =>  isset( $this->carp_codi ) ? (int) $this->carp_codi : null,
            'name'              =>  isset( $this->name ) ? $this->name : null,
            'status'            =>  isset( $this->carp_estado ) ? (bool) $this->carp_estado : null,
            'filed_count'       => isset( $this->filed_count ) ? (int) $this->filed_count : null,
            'read_count'        => isset( $this->read_count ) ? (int) $this->read_count : null,
            'unread_count'      => isset( $this->unread_count ) ? (int) $this->unread_count : null,
        ];
    }
}