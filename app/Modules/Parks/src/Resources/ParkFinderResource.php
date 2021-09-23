<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkFinderResource extends JsonResource
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
            'id'        =>  (int) isset( $this->Id ) ? (int) $this->Id : null,
            'code'      =>  isset( $this->Id_IDRD ) ? toUpper($this->Id_IDRD) : null,
            'name'      =>  isset( $this->Nombre ) ? toUpper($this->Nombre) : null,
            'scale_id'  =>  isset( $this->Id_Tipo ) ? (int) $this->Id_Tipo : null,
            'scale'     =>  isset( $this->scale->Tipo ) ? toUpper($this->scale->Tipo) : null,
            'locality_id'  =>  isset( $this->Id_Localidad ) ? (int) $this->Id_Localidad : null,
            'locality'  =>  isset( $this->location->Localidad ) ? toUpper($this->location->Localidad) : null,
            'address'   =>  isset( $this->Direccion ) ? toUpper($this->Direccion) : null,
            'upz_code'  =>  isset( $this->Upz ) ? (string) $this->Upz : null,
            'upz'       =>  isset( $this->upz_name->Upz ) ? toUpper($this->upz_name->Upz) : null,
            'color'     =>  isset( $this->Id_Tipo ) ? $this->getColor((int) $this->Id_Tipo) : 'grey',
            'status_id' =>  isset( $this->Estado ) && $this->Estado != 0 ? (int) $this->Estado : null,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'sectors'   =>  SectorResource::collection( $this->whenLoaded('sectors') ),
            '_links'    => [
              [
                  'rel'     => 'self',
                  'type'    =>  'GET',
                  'href'    => route('parks.show', ['park' => $this])
              ],
              [
                  'rel'     => 'create',
                  'type'    =>  'POST',
                  'href'    => route('parks.store')
              ],
              [
                  'rel'     => 'update',
                  'type'    =>  'PUT/PATCH',
                  'href'    => route('parks.update', ['park' => $this])
              ],
              [
                  'rel'     => 'delete',
                  'type'    =>  'DELETE',
                  'href'    => route('parks.destroy', ['park' => $this])
              ],
            ],
        ];
    }

    public function getColor($id = null)
    {
        switch ($id) {
            case 1:
            case 2:
            case 3:
                return 'success';
                break;
            default;
                return 'grey';
                break;
        }
    }
}
