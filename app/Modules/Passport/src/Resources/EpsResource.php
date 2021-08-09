<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
                'text' => "#",
                'value'  =>  "id",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.name')),
                'value'  =>  "name",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('passport.validations.created_at')),
                'value'  =>  "created_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('passport.validations.updated_at')),
                'value'  =>  "updated_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('passport.validations.actions')),
                'value'  =>  "actions",
                'sortable' => false
            ],
        ];
    }
}
