<?php


namespace App\Modules\CitizenPortal\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CitizenResource extends JsonResource
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
            'email'      =>  isset($this->email) ? (string) $this->email : null,
            'verified_at'    =>  isset($this->verified_at) ? $this->verified_at->format('Y-m-d H:i:s') : null,
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
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('citizen.validations.email')),
                'value'  =>  "email",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('citizen.validations.verified_at')),
                'value'  =>  "verified_at",
                'sortable' => true
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.created_at')),
                'value'  =>  "created_at",
                'sortable' => true
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.updated_at')),
                'value'  =>  "updated_at",
                'sortable' => true
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.actions')),
                'value'  =>  "actions",
                'sortable' => false
            ],
        ];
    }
}
