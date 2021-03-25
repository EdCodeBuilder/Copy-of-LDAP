<?php


namespace App\Modules\Contractors\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WareHouseResource extends JsonResource
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
            'id'           =>  isset($this->pvd_codi) ? (int) $this->pvd_codi : null,
            'document'     =>  isset($this->ter_carg) ? (int) $this->ter_carg : null,
            'name'         =>  isset($this->act_desc) ? $this->act_desc : null,
            'user'         =>  isset($this->aud_usua) ? $this->aud_usua : null,
            'quantity'     =>  isset($this->act_cant) ? (int) $this->act_cant : null,
        ];
    }
}
