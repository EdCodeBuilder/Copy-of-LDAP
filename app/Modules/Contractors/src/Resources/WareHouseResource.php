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
            'id'           =>  isset($this->act_codi) ? (int) $this->act_codi : null,
            'consecutive'  =>  isset($this->consecutive) ? (int) $this->consecutive : null,
            'document'     =>  isset($this->ter_carg) ? (int) $this->ter_carg : null,
            'responsable'  =>  isset($this->ter_resp) ? (int) $this->ter_resp : null,
            'name'         =>  isset($this->act_desc) ? $this->act_desc : null,
            'quantity'     =>  isset($this->act_cant) ? (int) $this->act_cant : null,
            'value'        =>  isset($this->value) ? (int) $this->value : null,
        ];
    }

    public static function headers()
    {
        return [
            [
                'sortable'  => false,
                'text' => "#",
                'value'  =>  "consecutive",
            ],
            [
                'sortable'  => false,
                'text' => "Descripción",
                'value'  =>  "name",
            ],
            [
                'text' => "Placa",
                'value'  =>  "id",
            ],
            [
                'sortable'  => false,
                'text' => "Cantidad",
                'value'  =>  "quantity",
            ],
            [
                'sortable'  => false,
                'text' => "Valor histórico",
                'value'  =>  "value",
            ],
        ];
    }
}
