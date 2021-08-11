<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PassportResource extends JsonResource
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
            'id'  => isset($this->id) ? (int) $this->id : null,
            'user'  => isset($this->user) ? (int) $this->user : null,
            'full_name' => isset($this->full_name) ? (string) $this->full_name : null,
            'first_name'    => isset($this->first_name) ? (string) $this->first_name : null,
            'middle_name'   => isset($this->middle_name) ? (string) $this->middle_name : null,
            'first_last_name'   => isset($this->first_last_name) ? (string) $this->first_last_name : null,
            'second_last_name'  => isset($this->second_last_name) ? (string) $this->second_last_name : null,
            'card_name' => isset($this->card_name) ? (string) $this->card_name : null,
            'document'  => isset($this->document) ? (int) $this->document : null,
            'document_type_id' => isset($this->document_type) ? (int) $this->document_type : null,
            'document_type_name'    => isset($this->document_type_name) ? (string) $this->document_type_name : null,
            'birthdate'  => isset($this->birthday) ? $this->birthday->format('Y-m-d') : null,
            'age'  => isset($this->birthday) ? (int) $this->birthday->age : null,
            'sex_id'    => isset($this->gender) ? (int) $this->gender : null,
            'gender_name'   => isset($this->gender_name) ? (string) $this->gender_name : null,
            'file' => isset($this->file) ? (string) $this->file : null,
            'country_id' => null,
            'state_id' => null,
            'city_id' => null,
            'country'   => isset($this->country) ? (int) $this->country : null,
            'country_name'  => isset($this->country_name) ? (string) $this->country_name : null,
            'state'  => isset($this->state_id) ? (int) $this->state_id : null,
            'state_name' => isset($this->state) ? (string) $this->state : null,
            'city'  => isset($this->city) ? (int) $this->city : null,
            'city_name' => isset($this->city_name) ? (string) $this->city_name : null,
            'locality_id'  => isset($this->location) ? (int) $this->location : null,
            'location_name' => isset($this->location_name) ? (string) $this->location_name : null,
            'address'   => isset($this->address) ? (string) $this->address : null,
            'stratum'   => isset($this->stratum) ? (int) $this->stratum : null,
            'mobile'    => isset($this->mobile) ? (int) $this->mobile : null,
            'phone' => isset($this->phone) ? (int) $this->phone : null,
            'email' => isset($this->email) ? (string) $this->email : null,
            'pensionary'   => isset($this->retired) ? Str::ucfirst($this->retired) : null,
            'retired'   => isset($this->retired) ? Str::ucfirst($this->retired) : null,
            'interest_id'   => isset($this->hobbies) ? (int) $this->hobbies : null,
            'hobbies'   => isset($this->hobbies) ? (int) $this->hobbies : null,
            'hobbies_name'  => isset($this->hobbies_name) ? (string) $this->hobbies_name : null,
            'eps_id'   => isset($this->eps) ? (int) $this->eps : null,
            'eps_name'  => isset($this->eps_name) ? (string) $this->eps_name : null,
            'cade_id' => isset($this->supercade) ? (int) $this->supercade : null,
            'supercade' => isset($this->supercade) ? (int) $this->supercade : null,
            'supercade_name'    => isset($this->supercade_name) ? (string) $this->supercade_name : null,
            'observations'  => isset($this->observations) ? (string) $this->observations : null,
            'question_1'    => isset($this->question_1) ? (string) $this->question_1 : null,
            'question_2'    => isset($this->question_2) ? (string) $this->question_2 : null,
            'question_3'    => isset($this->question_3) ? (string) $this->question_3 : null,
            'question_4'    => isset($this->question_4) ? (string) $this->question_4 : null,
            'downloads' => isset($this->downloads) ? (int) $this->downloads : null,
            'renew' => isset($this->renew) ? (int) $this->renew : null,
            'renew_data' => RenewalResource::collection($this->whenLoaded('renewals')),
            'user_cade_id' => isset($this->user_cade_id) ? (int) $this->user_cade_id : null,
            'user_cade_document' => isset($this->user_cade_document) ? (int) $this->user_cade_document : null,
            'user_cade_fullname' => isset($this->user_cade_fullname) ? (string) $this->user_cade_fullname : null,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function table($sortable = true)
    {
        $table = [
            'headers' => [
                [
                    'align' => "right",
                    'text' => "#",
                    'value'  =>  "id",
                    'sortable'    => $sortable,
                    'icon'  =>  'mdi-pound',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.name')),
                    'value'  =>  "full_name",
                    'sortable'    => $sortable,
                    'icon'  =>  'mdi-dots-horizontal',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.document_type_id')),
                    'value'  =>  "document_type_name",
                    'sortable'    => $sortable,
                    'icon'  =>  'mdi-card-account-details',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.document')),
                    'value'  =>  "document",
                    'sortable'    => $sortable,
                    'icon'  =>  'mdi-numeric',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.birthdate')),
                    'value'  =>  "birthdate",
                    'sortable'    => $sortable,
                    'icon'  =>  'mdi-calendar',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.age')),
                    'value'  =>  "age",
                    'sortable'    => false,
                    'icon'  =>  'mdi-calendar',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.file')),
                    'value'  =>  "file",
                    'sortable'    => false,
                    'icon'  =>  'mdi-paperclip',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.actions')),
                    'sortable'    => false,
                    'value'  =>  "actions",
                ],
            ],
            'expanded' => [
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.sex_id')),
                    'value'  =>  "gender_name",
                    'icon'  =>  'mdi-human-male-female',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.country_id')),
                    'value'  =>  "country_name",
                    'icon'  =>  'mdi-map',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.state_id')),
                    'value'  =>  "state_name",
                    'icon'  =>  'mdi-map-marker',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.city_id')),
                    'value'  =>  "city_name",
                    'icon'  =>  'mdi-crosshairs-gps',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.locality_id')),
                    'value'  =>  "location_name",
                    'icon'  =>  'mdi-sign-real-estate',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.address')),
                    'value'  =>  "address",
                    'icon'  =>  'mdi-routes',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.stratum')),
                    'value'  =>  "stratum",
                    'icon'  =>  'mdi-layers',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.mobile')),
                    'value'  =>  "mobile",
                    'icon'  =>  'mdi-cellphone',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.phone')),
                    'value'  =>  "phone",
                    'icon'  =>  'mdi-phone',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.email')),
                    'value'  =>  "email",
                    'icon'  =>  'mdi-email-outline',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.pensionary')),
                    'value'  =>  "retired",
                    'icon'  =>  'mdi-cash-100',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.interest_id')),
                    'value'  =>  "hobbies_name",
                    'icon'  =>  'mdi-layers',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.eps')),
                    'value'  =>  "eps_name",
                    'icon'  =>  'mdi-hospital',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.supercade_name')),
                    'value'  =>  "supercade_name",
                    'icon'  =>  'mdi-domain',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.observations')),
                    'value'  =>  "observations",
                    'icon'  =>  'mdi-text',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.question_1')),
                    'value'  =>  "question_1",
                    'icon'  =>  'mdi-help',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.question_2')),
                    'value'  =>  "question_2",
                    'icon'  =>  'mdi-help',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.question_3')),
                    'value'  =>  "question_3",
                    'icon'  =>  'mdi-help',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.question_4')),
                    'value'  =>  "question_4",
                    'icon'  =>  'mdi-help',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.downloads')),
                    'value'  =>  "downloads",
                    'icon'  =>  'mdi-cloud-download',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.user_cade')),
                    'value'  =>  "user_cade_document",
                    'icon'  =>  'mdi-card-account-details',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.user_cade_name')),
                    'value'  =>  "user_cade_fullname",
                    'icon'  =>  'mdi-face',
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('passport.validations.created_at')),
                    'value'  =>  "created_at",
                    'icon'  =>  'mdi-calendar',
                ]
            ]
        ];
        return $table;
    }
}
