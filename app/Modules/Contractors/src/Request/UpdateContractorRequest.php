<?php


namespace App\Modules\Contractors\src\Request;


use App\Models\Security\Area;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'document_type_id'  =>  'required|numeric|exists:mysql_ldap:document_types,id',
            // 'document'  =>  'required|numeric|unique:mysql_contractors.contractors,document,'.$this->route('contractor')->id,
            'name'  =>  'required|string|min:3|max:191',
            'surname'   =>  'required|string|min:3|max:191',
            'birthdate' =>  'required|date|date_format:Y-m-d',
            'sex_id' =>  'required|numeric|exists:mysql_sim.genero,Id_Genero',
            'email' =>  'required|email',
            'institutional_email'   =>  'nullable|email',
            'phone'   =>  'required|numeric',
            'eps_id'    =>  'required|numeric|exists:mysql_ldap.eps,id',
            'eps'   =>  'required_if:eps_id,62|string|nullable|min:3|max:191',
            'afp_id'    =>  'required|numeric|exists:mysql_ldap.afp,id',
            'afp'   =>  'required_if:afp_id,10|string|nullable|min:3|max:191',
            'residence_country_id'  =>  'required|numeric|exists:mysql_ldap.countries,id',
            'residence_state_id'    =>  'required|numeric|exists:mysql_ldap.states,id',
            'residence_city_id' =>  'required|numeric|exists:mysql_ldap.cities,id',
            'locality_id'   =>  'required|numeric|sometimes',
            'upz_id'    =>  'required|numeric|sometimes',
            'neighborhood_id'   =>  'required|numeric|sometimes',
            'neighborhood'  =>  'string|nullable|min:3|max:191',
            'address'  =>  'string|required|min:3|max:191',
            // Contract
            'contract_id' =>  'required|numeric|exists:mysql_contractors.contracts,id',
            // 'contract' =>  'required|string',
            'transport' =>  'required|boolean',
            'position'  =>  'required|string',
            // 'start_date'    =>  'required|date|date_format:Y-m-d|before:final_date',
            // 'final_date'    =>  'required|date|date_format:Y-m-d|after:start_date',
            // 'total' =>  'required|numeric',
            'day'   =>  'required|array',
            'risk'  =>  'required|numeric|between:1,5',
            'subdirectorate_id'    =>  'required|numeric|exists:mysql_ldap.subdirectorates,id',
            'dependency_id'    =>  'required|numeric|exists:mysql_ldap.areas,id',
            'other_dependency_subdirectorate'    =>  [
                Rule::requiredIf(function () {
                    $area = Area::query()->where('id', $this->get('dependency_id'))->first();
                    return isset($area->name) && $area->name === 'OTRO';
                }),
                'nullable',
                'string',
            ],
            'supervisor_email'  =>  'nullable|email',
            // TODO: Third party
            // 'rut'   =>  'required|file|mimes:pdf',
            // 'bank'  =>  'required|file|mimes:pdf',

            // 'contract_type_id' =>  'required|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'document_type_id'  =>  'tipo de documento',
            'document'  =>  'número de documento',
            'name'  =>  'required|string|min:3|max:191',
            'surname'   =>  'required|string|min:3|max:191',
            'birthdate' =>  'fecha de nacimiento',
            'sex_id' =>  'sexo',
            'email' =>  'correo personal',
            'institutional_email'   =>  'correo institucional',
            'phone'   =>  'teléfono de contácto',
            'eps_id'    =>  'eps',
            'eps'    =>  'otra eps',
            'afp_id'    =>  'fondo de pensiones',
            'afp'    =>  'otro fondo de pensiones',
            'residence_country_id'  =>  'país',
            'residence_state_id'    =>  'departamaneto',
            'residence_city_id' =>  'ciudad',
            'locality_id'   =>  'localidad',
            'upz_id'    =>  'upz',
            'neighborhood_id'   =>  'barrio',
            'neighborhood'  =>  'otro nombre del bario',
            'address'  =>  'dirección',
            // Contrato
            'contract_id'    =>  'contrato',
            'contract_type'    =>  'tipo de trámite',
            'transport' =>  'se suministra transporte',
            'contract'    =>  'contrato',
            'position'  =>  'cargo a desempeñar',
            'start_date'    =>  'fecha de inicio del contrato',
            'final_date'    =>  'fecha de terminación del contrato',
            'total' =>  'valor del contrato o adición',
            'day'   =>  'día que no trabaja',
            'risk'  =>  'nivel de riesgo',
            'subdirectorate_id'    =>  'subdirección',
            'dependency_id'    =>  'dependencia',
            'other_dependency_subdirectorate'  =>  'otra dependencia o subdirección',
            'supervisor_email'  =>  'correo del supervisor',
            'rut'  =>  'certificado rut',
            'bank'  =>  'certificación bancaria',
        ];
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        $validator->sometimes('locality_id', 'exists:mysql_parks.localidad,Id_Localidad', function($input)
        {
            return $input->locality_id != 9999;
        });

        $validator->sometimes('upz_id', 'exists:mysql_parks.upz,Id_Upz', function($input)
        {
            return $input->upz_id != 9999;
        });

        $validator->sometimes('neighborhood_id', 'exists:mysql_parks.Barrios,IdBarrio', function($input)
        {
            return $input->neighborhood_id != 9999;
        });

        return $validator;
    }
}
