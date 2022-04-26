<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = isset($this->name) ? (string) $this->name : null;
        $id = isset($this->id) ? (int) $this->id : null;
        return [
            'id'        =>  $id,
            'user_id'      =>  isset($this->user_id) ? (int) $this->user_id : null,
            'profile_type_id'      =>  isset($this->profile_type_id) ? (int) $this->profile_type_id : null,
            'profile_type'      =>  isset($this->profile_type) ? (string) $this->profile_type : null,
            'document_type_id'      =>  isset($this->document_type_id) ? (int) $this->document_type_id : null,
            'document_type'      =>  isset($this->document_type) ? (string) $this->document_type : null,
            'document'      =>  isset($this->document) ? (int) $this->document : null,
            'name'      =>  $name,
            's_name'      =>  isset($this->s_name) ? (string) $this->s_name : null,
            'surname'      =>  isset($this->surname) ? (string) $this->surname : null,
            's_surname'      =>  isset($this->s_surname) ? (string) $this->s_surname : null,
            'full_name'      =>  isset($this->full_name) ? (string) $this->full_name : null,
            'email'      =>  isset($this->email) ? toLower($this->email) : null,
            'mobile_phone'      =>  isset($this->mobile_phone) && $this->mobile_phone != 0 ? (int) $this->mobile_phone : null,
            'phone'      =>  isset($this->mobile_phone) && $this->mobile_phone != 0 ? (int) $this->mobile_phone : null,
            'whatsapp'      =>  isset($this->mobile_phone)
                ? whatsapp_link(
                    $this->mobile_phone,
                    "👋 ¡Hola $name! te escribimos desde el Portal Ciudadano del IDRD"
                )
                : null,
            'sex_id'      =>  isset($this->sex_id) ? (int) $this->sex_id : null,
            'sex'      =>  isset($this->sex) ? (string) $this->sex : null,
            'blood_type_id'      =>  isset($this->blood_type_id) ? (int) $this->blood_type_id : null,
            'blood_type'      =>  isset($this->blood_type) ? (string) $this->blood_type : null,
            'birthdate'      =>  isset($this->birthdate) ? $this->birthdate->format('Y-m-d') : null,
            'age'               =>  isset($this->birthdate->age) ? (int) $this->birthdate->age : null,
            'country_birth_id'      =>  isset($this->country_birth_id) ? (int) $this->country_birth_id : null,
            'country_birth'      =>  isset($this->country_birth) ? (string) $this->country_birth : null,
            'state_birth_id'      =>  isset($this->state_birth_id) ? (int) $this->state_birth_id : null,
            'state_birth'      =>  isset($this->state_birth) ? (string) $this->state_birth : null,
            'city_birth_id'      =>  isset($this->city_birth_id) ? (int) $this->city_birth_id : null,
            'city_birth'      =>  isset($this->city_birth) ? (string) $this->city_birth : null,
            'country_residence_id'      =>  isset($this->country_residence_id) ? (int) $this->country_residence_id : null,
            'country_residence'      =>  isset($this->country_residence) ? (string) $this->country_residence : null,
            'state_residence_id'      =>  isset($this->state_residence_id) ? (int) $this->state_residence_id : null,
            'state_residence'      =>  isset($this->state_residence) ? (string) $this->state_residence : null,
            'city_residence_id'      =>  isset($this->city_residence_id) ? (int) $this->city_residence_id : null,
            'city_residence'      =>  isset($this->city_residence) ? (string) $this->city_residence : null,
            'locality_id'      =>  isset($this->locality_id) ? (int) $this->locality_id : null,
            'locality'      =>  isset($this->locality) ? (string) $this->locality : null,
            'upz_id'      =>  isset($this->upz_id) ? (int) $this->upz_id : null,
            'upz'      =>  isset($this->upz) ? (string) $this->upz : null,
            'neighborhood_id'      =>  isset($this->neighborhood_id) ? (int) $this->neighborhood_id : null,
            'neighborhood'      =>  isset($this->neighborhood) ? (string) $this->neighborhood : null,
            'other_neighborhood_name'      =>  isset($this->other_neighborhood_name) ? (string) $this->other_neighborhood_name : null,
            'address'      =>  isset($this->address) ? (string) $this->address : null,
            'stratum'      =>  isset($this->stratum) ? (int) $this->stratum : null,
            'ethnic_group_id'      =>  isset($this->ethnic_group_id) ? (int) $this->ethnic_group_id : null,
            'ethnic_group'      =>  isset($this->ethnic_group) ? (string) $this->ethnic_group : null,
            'population_group_id'      =>  isset($this->population_group_id) ? (int) $this->population_group_id : null,
            'population_group'      =>  isset($this->population_group) ? (string) $this->population_group : null,
            'gender_id'      =>  isset($this->gender_id) ? (int) $this->gender_id : null,
            'gender'      =>  isset($this->gender) ? (string) $this->gender : null,
            'sexual_orientation_id'      =>  isset($this->sexual_orientation_id) ? (int) $this->sexual_orientation_id : null,
            'sexual_orientation'      =>  isset($this->sexual_orientation) ? (string) $this->sexual_orientation : null,
            'eps_id'      =>  isset($this->eps_id) ? (int) $this->eps_id : null,
            'eps'      =>  isset($this->eps) ? (string) $this->eps : null,
            'has_disability'      =>  isset($this->has_disability) ? (string) $this->has_disability : null,
            'disability_id'      =>  isset($this->disability_id) ? (int) $this->disability_id : null,
            'disability'      =>  isset($this->disability) ? (string) $this->disability : null,
            'contact_name'      =>  isset($this->contact_name) ? (string) $this->contact_name : null,
            'contact_phone'      =>  isset($this->contact_phone) ? (int) $this->contact_phone : null,
            'contact_relationship'      =>  isset($this->contact_relationship) ? (int) $this->contact_relationship : null,
            'contact_relationship_name'      =>  isset($this->contact_relationship_name) ? (string) $this->contact_relationship_name : null,
            'verified_at'      =>  isset($this->verified_at) ? (string) $this->verified_at : null,
            'assigned_by_id'      =>  isset($this->assigned_by_id) ? (int) $this->assigned_by_id : null,
            'full_name_assignor'      =>  isset($this->full_name_assignor) ? (string) $this->full_name_assignor : null,
            'assignor_name'      =>  isset($this->assignor_name) ? (string) $this->assignor_name : null,
            'assignor_surname'      =>  isset($this->assignor_surname) ? (string) $this->assignor_surname : null,
            'assignor_document'      =>  isset($this->assignor_document) ? (string) $this->assignor_document : null,
            'assigned_at'      =>  isset($this->assigned_at) ? (string) $this->assigned_at : null,
            'checker_id'      =>  isset($this->checker_id) ? (int) $this->checker_id : null,
            'full_name_verifier'      =>  isset($this->full_name_verifier) ? (string) $this->full_name_verifier : null,
            'checker_name'      =>  isset($this->checker_name) ? (string) $this->checker_name : null,
            'checker_surname'      =>  isset($this->checker_surname) ? (string) $this->checker_surname : null,
            'checker_document'      =>  isset($this->checker_document) ? (string) $this->checker_document : null,
            'status_id'      =>  isset($this->status_id) ? (int) $this->status_id : Profile::PENDING,
            'color' => $this->getColor(),
            'status'      =>  isset($this->status) ? (string) $this->status : $this->getStatus(),
            'activities_count'      =>  $id ? CitizenSchedule::query()->where('profile_id', $id)->count() : 0,
            'observations_count'      =>  isset($this->observations_count) ? (int) $this->observations_count : 0,
            'files_count'      =>  isset($this->files_count) ? (int) $this->files_count : 0,
            'pending_files_count'      =>  isset($this->pending_files_count) ? (int) $this->pending_files_count : 0,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            'headers'   => [
                [
                    'icon'  =>  'mdi-pound',
                    'align' => "right",
                    'text' => "#",
                    'value'  =>  "id",
                    'sortable' => true
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('citizen.validations.actions')),
                    'value'  =>  "actions",
                    'sortable' => false
                ],
                [
                    'icon'  =>  'mdi-checkbox-marked-circle-outline',
                    'align' => "center",
                    'text' => Str::ucfirst(__('citizen.validations.status')),
                    'value'  =>  "status",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-card-account-details-star-outline',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.profile_type')),
                    'value'  =>  "profile_type",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-card-account-details',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.document_type')),
                    'value'  =>  "document_type",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-numeric',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.document')),
                    'value'  =>  "document",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-dots-horizontal',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.full_name')),
                    'value'  =>  "full_name",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-calendar',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.birthdate')),
                    'value'  =>  "birthdate",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-calendar',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.age')),
                    'value'  =>  "age",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-email',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.email')),
                    'value'  =>  "email",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-whatsapp',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.phone')),
                    'value'  =>  "mobile_phone",
                    'sortable' => false
                ],
            ],
            'expanded'  => [
                [
                    'icon'  => 'mdi-dots-horizontal',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.name')),
                    'value'  =>  "name",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-dots-horizontal',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.s_name')),
                    'value'  =>  "s_name",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-dots-horizontal',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.surname')),
                    'value'  =>  "surname",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-dots-horizontal',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.s_surname')),
                    'value'  =>  "s_surname",
                    'sortable' => false
                ],
                [
                    'icon'  => 'mdi-human-male-female',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.sex')),
                    'value'  =>  "sex",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-water',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.blood_type')),
                    'value'  =>  "blood_type",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-map',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.country_birth')),
                    'value'  =>  "country_birth",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-map-marker',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.state_birth')),
                    'value'  =>  "state_birth",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-crosshairs-gps',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.city_birth')),
                    'value'  =>  "city_birth",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-map',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.country_residence')),
                    'value'  =>  "country_residence",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-map-marker',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.state_residence')),
                    'value'  =>  "state_residence",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-crosshairs-gps',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.city_residence')),
                    'value'  =>  "city_residence",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-sign-real-estate',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.locality')),
                    'value'  =>  "locality",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-tag',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.upz')),
                    'value'  =>  "upz",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-city',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.neighborhood')),
                    'value'  =>  "neighborhood",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-city',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.other_neighborhood_name')),
                    'value'  =>  "other_neighborhood_name",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-routes',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.address')),
                    'value'  =>  "address",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-layers',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.stratum')),
                    'value'  =>  "stratum",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-sitemap',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.ethnic_group')),
                    'value'  =>  "ethnic_group",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-nature-people',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.population_group')),
                    'value'  =>  "population_group",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-google-circles-extended',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.gender')),
                    'value'  =>  "gender",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-gender-male-female-variant',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.sexual_orientation')),
                    'value'  =>  "sexual_orientation",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-hospital',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.eps')),
                    'value'  =>  "eps",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-human-wheelchair',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.has_disability')),
                    'value'  =>  "has_disability",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-human-wheelchair',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.disability')),
                    'value'  =>  "disability",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-human-capacity-decrease',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.contact_relationship')),
                    'value'  =>  "contact_relationship_name",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-account',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.contact_name')),
                    'value'  =>  "contact_name",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-phone',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.contact_phone')),
                    'value'  =>  "contact_phone",
                    'sortable' => true
                ],
                [
                    'icon'   => 'mdi-account',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.assignor_name')),
                    'value'  =>  "full_name_assignor",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-numeric',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.assignor_document')),
                    'value'  =>  "assignor_document",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-calendar',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.assigned_at')),
                    'value'  =>  "assigned_at",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-account',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.checker_name')),
                    'value'  =>  "full_name_verifier",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-numeric',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.checker_document')),
                    'value'  =>  "checker_document",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-calendar',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.verified_at')),
                    'value'  =>  "verified_at",
                    'sortable' => true
                ],
                [
                    'icon'  => 'mdi-calendar',
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.created_at')),
                    'value'  =>  "created_at",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.updated_at')),
                    'value'  =>  "updated_at",
                    'sortable' => true
                ],
            ]
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $status = Status::find(Profile::PENDING);
        return isset($status->name) ? (string) $status->name : null;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        $status = isset($this->status_id) ? (int) $this->status_id: Profile::PENDING;
        return Status::getColor($status);
    }
}
