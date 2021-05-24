<?php


namespace App\Modules\Contractors\src\Resources;


use App\Modules\Contractors\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ContractorResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contract = $this->contracts()->latest()->first();
        $transport = isset($contract->transport) ? $contract->transport : null;
        return [
            'id'                    =>  isset($this->id) ? (int) $this->id : null,
            // Start Contract Data
            'contract_id'           =>  isset($contract->id) ? (int) $contract->id : null,
            'contract_type'         =>  isset($contract->contract_type->name) ? $contract->contract_type->name : null,
            'contract'              =>  isset($contract->contract) ? $contract->contract : null,
            'transport'                         =>  $transport,
            'transport_text'                    =>  $transport ? 'SI' : 'NO',
            'position'                          =>  isset($contract->position) ? $contract->position : null,
            'start_date'                        =>  isset($contract->start_date) ? $contract->start_date->format('Y-m-d') : null,
            'final_date'                        =>  isset($contract->final_date) ? $contract->final_date->format('Y-m-d') : null,
            'start_suspension_date'             =>  isset($contract->start_suspension_date) ? $contract->start_suspension_date->format('Y-m-d') : null,
            'final_suspension_date'             =>  isset($contract->final_suspension_date) ? $contract->final_suspension_date->format('Y-m-d') : null,
            'total'                             =>  isset($contract->total) ? (int) $contract->total : null,
            'day'                               =>  isset($contract->day) ? $contract->day : [],
            'day_string'                        =>  isset($contract->day) ? $contract->getOriginal('day') : null,
            'risk'                              =>  isset($contract->risk) ? $contract->risk : null,
            'subdirectorate'                    =>  isset($contract->subdirectorate->name) ? $contract->subdirectorate->name : null,
            'dependency'                        =>  isset($contract->dependency->name) ? $contract->dependency->name : null,
            'other_dependency_subdirectorate'   =>  isset($contract->other_dependency_subdirectorate) ? $contract->other_dependency_subdirectorate : null,
            'supervisor_email'                  =>  isset($contract->supervisor_email) ? $contract->supervisor_email : null,
            'lawyer'                            =>  isset($contract->lawyer->full_name) ? $contract->lawyer->full_name : null,
            'files'                             =>  isset($contract->files) ? FileResource::collection( $contract->files ) : [],
            'secop_file'                        =>  isset($contract->files) ? $contract->files->where('file_type_id', 2)->count() : 0,
            'arl_file'                          =>  isset($contract->files) ? $contract->files->where('file_type_id', 1)->count() : 0,
            // End Contract Data
            'document_type_id'      =>  isset($this->document_type_id) ? (int) $this->document_type_id : null,
            'document_type'         =>  isset($this->document_type->name) ? $this->document_type->name : null,
            'document'              =>  isset($this->document) ? $this->document : null,
            'name'                  =>  isset($this->name) ? $this->name : null,
            'surname'               =>  isset($this->surname) ? $this->surname : null,
            'email'                 =>  isset($this->email) ? $this->email : null,
            'phone'                 =>  isset($this->phone) ? $this->phone : null,
            $this->mergeWhen(auth()->user()->isA(Roles::ROLE_ARL, Roles::ROLE_ADMIN), [
                'modifiable_link'  =>  isset($this->modifiable_link) ? $this->modifiable_link : null,
                'whatsapp_link'   =>  isset($this->whatsapp_link) ? $this->whatsapp_link : null,
            ]),
            $this->mergeWhen(auth()->user()->isA(Roles::ROLE_ARL, Roles::ROLE_ADMIN, Roles::ROLE_THIRD_PARTY), [
                'birthdate'             =>  isset($this->birthdate) ? $this->birthdate->format('Y-m-d') : null,
                'birthdate_country_id'  =>  isset($this->birthdate_country_id) ? (int) $this->birthdate_country_id : null,
                'birthdate_country'     =>  isset($this->birthdate_country->name) ? $this->birthdate_country->name : null,
                'birthdate_state_id'    =>  isset($this->birthdate_state_id) ? (int) $this->birthdate_state_id : null,
                'birthdate_state'       =>  isset($this->birthdate_state->name) ? $this->birthdate_state->name : null,
                'birthdate_city_id'     =>  isset($this->birthdate_city_id) ? (int) $this->birthdate_city_id : null,
                'birthdate_city'        =>  isset($this->birthdate_city->name) ? $this->birthdate_city->name : null,
                'age'                   =>  isset($this->birthdate) ? $this->birthdate->age : null,
                'sex_id'                =>  isset($this->sex_id) ? $this->sex_id : null,
                'sex'                   =>  isset($this->sex->name) ? $this->sex->name : null,
                'institutional_email'   =>  isset($this->institutional_email) ? $this->institutional_email : null,
                'eps_id'                =>  isset($this->eps_id) ? (int) $this->eps_id : null,
                'eps_name'              =>  isset($this->eps_name->name) ? $this->eps_name->name : null,
                'eps'                   =>  isset($this->eps) ? $this->eps : null,
                'afp_id'                =>  isset($this->afp_id) ? (int) $this->afp_id : null,
                'afp_name'              =>  isset($this->afp_name->name) ? $this->afp_name->name : null,
                'afp'                   =>  isset($this->afp) ? $this->afp : null,
                'residence_country_id'  =>  isset($this->residence_country_id) ? (int) $this->residence_country_id : null,
                'residence_country'     =>  isset($this->residence_country->name) ? $this->residence_country->name : null,
                'residence_state_id'    =>  isset($this->residence_state_id) ? (int) $this->residence_state_id : null,
                'residence_state'       =>  isset($this->residence_state->name) ? $this->residence_state->name : null,
                'residence_city_id'     =>  isset($this->residence_city_id) ? (int) $this->residence_city_id : null,
                'residence_city'        =>  isset($this->residence_city->name) ? $this->residence_city->name : null,
                'locality_id'           =>  $this->setLocalityId(),
                'locality'              =>  $this->setLocalityName(),
                'upz_id'                =>  $this->setUpzId(),
                'upz'                   =>  $this->setUpzName(),
                'neighborhood_id'       =>  $this->setNeighborhoodId(),
                'neighborhood_name'     =>  $this->setNeighborhoodName(),
                'neighborhood'          =>  isset($this->neighborhood) ? $this->neighborhood : null,
                'address'               =>  isset($this->address) ? $this->address : null,
                $this->merge(new ContractorCareerResource($this->careers()->latest()->first())),
            ]),

            'rut'                   =>  isset($this->rut) ? $this->getOriginal('rut') : null,
            'rut_file'              =>  isset($this->rut) ? $this->rut : null,
            'bank'                  =>  isset($this->bank) ? $this->getOriginal('bank') : null,
            'bank_file'             =>  isset($this->bank) ? $this->bank: null,
            'third_party'           =>  isset($this->third_party) ? (bool) $this->third_party: null,
            'third_party_text'      =>  $this->setThirdParty(),

            'contracts'             =>  ContractResource::collection($this->whenLoaded('contracts')),
            'contract_headers'      =>  ContractResource::headers(),
            'user_id'               =>  isset($this->user_id) ? (int) $this->user_id : null,
            'user'                  =>  isset($this->user->full_name) ? $this->user->full_name : null,
            'created_at'            =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'            =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            [
                'text' => "#",
                'value'  =>  "id",
            ],
            [
                'align' => "right",
                'text' => "Detalles",
                'value'  =>  "view",
            ],
            [
                'text' => 'Tipo de trámite',
                'value'  =>  'contract_type',
                // 'icon'  =>  'mdi-clipboard-text-outline',
            ],
            [
                'text' => 'Número de contrato',
                'value'  =>  'contract',
                // 'icon'  =>  'mdi-file',
            ],
            [
                'text' => "Secop",
                'value'  =>  "secop_file",
            ],
            [
                'text' => "ARL",
                'value'  =>  "arl_file",
            ],
            [
                'text'   =>  'Terceros',
                'value'   =>  'third_party_text',
            ],
            [
                'text' => "Nombres",
                'value'  =>  "name",
                'icon'  =>  'mdi-face',
            ],
            [
                'text' => "Apellidos",
                'value'  =>  "surname",
                'icon'  =>  'mdi-face',
            ],
            [
                'text' => "Tipo de documento",
                'value'  =>  "document_type",
                'icon'  =>  'mdi-card-account-details',
            ],
            [
                'align' => "right",
                'text' => "Documento",
                'value'  =>  "document",
                'icon'  =>  'mdi-numeric',
            ],
            [
                'align' => "right",
                'text' => "E-mail",
                'value'  =>  "email",
                'icon'  =>  'mdi-email-outline',
            ],
            [
                'align' => "right",
                'text' => "Teléfono",
                'value'  =>  "phone",
                'icon'  =>  'mdi-phone',
            ],
        ];
    }

    public static function additionalData()
    {
        return auth()->user()->isA(Roles::ROLE_ARL, Roles::ROLE_ADMIN, Roles::ROLE_THIRD_PARTY)
            ? [
                [
                    'align' => "right",
                    'label' => "Fecha de nacimiento",
                    'field'  =>  "birthdate",
                    'icon'  =>  'mdi-calendar',
                ],
                [
                    'label'  => 'País de nacimiento',
                    'field'  => 'birthdate_country',
                    'icon'   => 'mdi-map',
                ],
                [
                    'label'  => 'Departamento de nacimiento',
                    'field'  => 'birthdate_state',
                    'icon'   => 'mdi-map-marker',
                ],
                [
                    'label'  => 'Ciudad de nacimiento',
                    'field'  => 'birthdate_city',
                    'icon'   => 'mdi-crosshairs-gps',
                ],
                [
                    'label'  => 'Sexo',
                    'field'  => 'sex',
                    'icon'  =>  'mdi-human-male-female',
                ],
                [
                    'label'  => 'Se suministra transporte',
                    'field'  => 'transport_text',
                    // 'icon'  =>  'mdi-car',
                ],
                [
                    'label'  => 'Correo Institucional',
                    'field'  => 'institutional_email',
                    'icon'  =>  'mdi-email-outline',
                ],
                [
                    'label'  => 'EPS',
                    'field'  => 'eps_name',
                    'icon'  =>  'mdi-hospital',
                ],
                [
                    'label'  => 'Otra EPS',
                    'field'  => 'eps',
                    'icon'  =>  'mdi-hospital',
                ],
                [
                    'label'  => 'Fondo de pensiones',
                    'field'  => 'afp_name',
                    'icon'   => 'mdi-piggy-bank-outline',
                ],
                [
                    'label'  => 'Otro Fondo de pensiones',
                    'field'  => 'afp',
                    'icon'   => 'mdi-piggy-bank-outline',
                ],
                [
                    'label'  => 'País de residencia',
                    'field'  => 'residence_country',
                    'icon'   => 'mdi-map',
                ],
                [
                    'label'  => 'Departamento de residencia',
                    'field'  => 'residence_state',
                    'icon'   => 'mdi-map-marker',
                ],
                [
                    'label'  => 'Ciudad de residencia',
                    'field'  => 'residence_city',
                    'icon'   => 'mdi-crosshairs-gps',
                ],
                [
                    'label'  => 'Localidad',
                    'field'  => 'locality',
                    'icon'   => 'mdi-sign-real-estate',
                ],
                [
                    'label'  => 'UPZ',
                    'field'  => 'upz',
                    'icon'   => 'mdi-tag',
                ],
                [
                    'label'  => 'Barrio',
                    'field'  => 'neighborhood_name',
                    'icon'   => 'mdi-city',
                ],
                [
                    'label'  => 'Otro Barrio',
                    'field'  => 'neighborhood',
                    'icon'   => 'mdi-city',
                ],
                [
                    'label'  => 'Dirección',
                    'field'  => 'address',
                    'icon'   => 'mdi-routes',
                ],
                [
                    'label'  => 'Cargo a desempeñar',
                    'field'  => 'position',
                    'icon'   => 'mdi-book-account-outline',
                ],
                [
                    'label'  => 'Nivel Académico',
                    'field'  => 'academic_level',
                    'icon'   => 'mdi-book',
                ],
                [
                    'label'  => 'TÍtulo Académico',
                    'field'  => 'career',
                    'icon'   => 'mdi-school',
                ],
                [
                    'label'  => '¿Graduado?',
                    'field'  => 'graduate_text',
                    'icon'   => 'mdi-book',
                ],
                [
                    'label'  => 'Último semestre o año aprobado',
                    'field'  => 'year_approved',
                    'icon'   => 'mdi-book-account-outline',
                ],
                [
                    'label'  => 'Fecha inicio contrato',
                    'field'  => 'start_date',
                    // 'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Fecha fin contrato',
                    'field'  => 'final_date',
                    // 'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Fecha inicio suspención',
                    'field'  => 'start_suspension_date',
                    // 'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Fecha fin suspención',
                    'field'  => 'final_suspension_date',
                    // 'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Valor total del contrato',
                    'field'  => 'total',
                    // 'icon'   => 'mdi-currency-usd',
                ],
                [
                    'label'  => 'Día(s) que no trabaja',
                    'field'  => 'day_string',
                    // 'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Nivel de riesgo',
                    'field'  => 'risk',
                    // 'icon'   => 'mdi-alert-outline',
                ],
                [
                    'label'  => 'Subdirección',
                    'field'  => 'subdirectorate',
                    // 'icon'   => 'mdi-domain',
                ],
                [
                    'label'  => 'Dependencia',
                    'field'  => 'dependency',
                    // 'icon'   => 'mdi-layers',
                ],
                [
                    'label'  => 'Otra subdirección o dependencia',
                    'field'  => 'other_dependency_subdirectorate',
                    // 'icon'   => 'mdi-domain',
                ],
                [
                    'label'  => 'Correo Supervisor',
                    'field'  => 'supervisor_email',
                    'icon'   => 'mdi-mail',
                ],
                [
                    'label'   =>  'RUT',
                    'field'   =>  'rut',
                    'icon'   => 'mdi-file',
                ],
                [
                    'label'   =>  'Certificación Bancaria',
                    'field'   =>  'bank',
                    'icon'    => 'mdi-currency-usd',
                ],
                [
                    'label'   =>  'Terceros creados',
                    'field'   =>  'third_party_text',
                    'icon'   =>   'mdi-archive-arrow-up',
                ],
                [
                    'label'   =>  'Creado por',
                    'field'   =>  'user',
                    'icon'   => 'mdi-account',
                ],
                [
                    'label'  => 'Fecha de Registro',
                    'field'  => 'created_at',
                    'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Fecha de Actualización',
                    'field'  => 'updated_at',
                    'icon'   => 'mdi-calendar',
                ],
            ]
            : [
                [
                    'label'   =>  'Creado por',
                    'field'   =>  'user',
                    'icon'   => 'mdi-account',
                ],
                [
                    'label'  => 'Fecha de Registro',
                    'field'  => 'created_at',
                    'icon'   => 'mdi-calendar',
                ],
                [
                    'label'  => 'Fecha de Actualización',
                    'field'  => 'updated_at',
                    'icon'   => 'mdi-calendar',
                ],
            ];
    }

    public function setThirdParty()
    {
        $tp = isset($this->third_party) ? (bool) $this->third_party: null;
        return $tp ? 'CON TERCERO' : 'SIN TERCERO';
    }

    public function setNeighborhoodId()
    {
        $id = isset($this->neighborhood_id) ? $this->neighborhood_id : null;
        return isset($this->neighborhood) ? 9999 : $id;
    }

    public function setNeighborhoodName()
    {
        $neighborhood = isset($this->neighborhood_name->name) ? $this->neighborhood_name->name : null;
        return is_null($neighborhood) ? 'OTRO' : $neighborhood;
    }

    public function setLocalityId() {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $locality = isset($this->locality_id) ? (int) $this->locality_id : null;
        return !is_null($state) && is_null($locality) ? 9999 : $locality;
    }

    public function setUpzId()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $upz = isset($this->upz_id) ? (int) $this->upz_id : null;
        return !is_null($state) && is_null($upz) ? 9999 : $upz;
    }

    public function setLocalityName()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $locality = isset($this->locality->name) ? $this->locality->name : null;
        return !is_null($state) && is_null($locality) ? 'OTRO' : $locality;
    }

    public function setUpzName()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $upz = isset($this->upz->name) ? $this->upz->name : null;
        return !is_null($state) && is_null($upz) ? 'OTRO' : $upz;
    }
}
