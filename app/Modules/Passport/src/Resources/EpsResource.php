<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class EpsResource extends JsonResource
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
            'id'        =>  isset($this->id) ? (int) $this->id : null,
            'name'      =>  isset($this->name) ? (string) $this->name : null,
            'status'    =>  isset($this->status) ? (bool) $this->status : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            [
                'align' => "right",
                'text' => "ID",
                'value'  =>  "id",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => "Nombre",
                'value'  =>  "name",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => "Fecha de creación",
                'value'  =>  "created_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => "Fecha de actualización",
                'value'  =>  "created_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => "Acciones",
                'value'  =>  "actions",
                'sortable' => false
            ],
        ];
    }
}
