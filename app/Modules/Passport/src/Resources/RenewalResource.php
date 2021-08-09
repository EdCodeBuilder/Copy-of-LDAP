<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RenewalResource extends JsonResource
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
            'passport_id'      =>  isset($this->passport_id) ? (int) $this->passport_id : null,
            'user_id'    =>  isset($this->user_id) ? (int) $this->user_id : null,
            $this->mergeWhen($this->relationLoaded('user'), [
                'user'    =>  isset($this->user->full_name) ? (string) $this->user->full_name : null,
                'user_document_type'    =>  isset($this->user->document_type->name) ? (string) $this->user->document_type->name : null,
                'user_document'    =>  isset($this->user->document) ? (int) $this->user->document : null,
            ]),
            'user_cade_id'    =>  isset($this->user_cade_id) ? (int) $this->user_cade_id : null,
            'user_cade_document'    =>  isset($this->user_cade_document) ? (int) $this->user_cade_document : null,
            'user_cade_name'    =>  isset($this->user_cade_name) ? (string) $this->user_cade_name : null,
            'supercade_id'    =>  isset($this->supercade_id) ? (int) $this->supercade_id : null,
            'supercade'    =>  isset($this->supercade) ? (string) $this->supercade : null,
            'denounce'    =>  isset($this->denounce) ? (string) $this->denounce : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers($additional = false)
    {
        $headers = [
            [
                'align' => "right",
                'text' => "#",
                'value'  =>  "id",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.passport')),
                'value'  =>  "passport_id",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.user_cade')),
                'value'  =>  "user_cade_document",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.user_cade_name')),
                'value'  =>  "user_cade_name",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.supercade_name')),
                'value'  =>  "supercade",
                'sortable' => true
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.denounce')),
                'value'  =>  "denounce",
                'sortable' => true
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('passport.validations.created_at')),
                'value'  =>  "created_at",
                'sortable' => true
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('passport.validations.actions')),
                'value'  =>  "actions",
                'sortable' => false
            ],
        ];
        $user = [
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.name')),
                'value'  =>  "user",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.document_type_id')),
                'value'  =>  "user_document_type",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('passport.validations.document')),
                'value'  =>  "user_document",
                'sortable' => false
            ],
        ];
        $res = array_insert_in_position($headers, $user, 2);
        return $additional ? $res : $headers;
    }
}
