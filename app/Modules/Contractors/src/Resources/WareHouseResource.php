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
            'value'        =>  isset($this->act_cost) ? (int) $this->act_cost : null,
            'currency'     =>  isset($this->act_cost) ? $this->currency($this->act_cost) : null,
        ];
    }

    /**
     * Datatable headers
     *
     * @return array[]
     */
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
                'sortable'  => false,
                'text' => "Placa",
                'value'  =>  "id",
            ],
            [
                'sortable'  => false,
                'text' => "Valor histórico",
                'value'  =>  "currency",
            ],
        ];
    }

    /**
     * @param mixed|int $value
     * @return int|mixed|string
     */
    public function currency($value = 0)
    {
        return is_numeric($value) ? "$ ".number_format($value, 2) : $value;
    }
}
