<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Forms\src\Models\Validation;
use App\Modules\Passport\src\Constants\Roles;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePassportAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(...Roles::all());;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document_type_id' =>  'required|numeric|exists:mysql_sim.tipo_documento,Id_TipoDocumento',
            'document'  =>  [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $user =  User::where('Cedula', $value)->first();
                    if (isset($user->Id_Persona) && $user->passport()->count() > 0) {
                        $fail("El número de documento $value ya se encuentra registrado en Pasaporte Vital");
                    }
                    if (isset($user->Id_Persona) && $user->passport_old()->count() > 0) {
                        $fail("El número de documento $value ya se encuentra registrado en Pasaporte Vital");
                    }
                },
            ],
            'first_name' =>  'required|min:3|max:45',
            'middle_name'   =>  'nullable|min:3|max:45',
            'first_last_name'    =>  'required|min:3|max:45',
            'second_last_name'    =>  'nullable|min:3|max:45',
            'birthdate'   =>  [
                'required',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) {
                    $age = Carbon::parse($value)->age;
                    $male_cant_create = $this->get('pensionary') == 'No' && $age < 60 && (int) $this->get('sex_id') == 1;
                    $female_cant_create = $this->get('pensionary') == 'No' && $age < 55 && (int) $this->get('sex_id') == 2;

                    if ($male_cant_create) {
                        $fail("El usuario de genero masculino no cumple con los requisitos de edad (60), tiene $age años.");
                    }
                    if ($female_cant_create) {
                        $fail("El usuario de genero femenino no cumple con los requisitos de edad (55), tiene $age años.");
                    }
                },
            ],
            'sex_id'  =>  'required|numeric|exists:mysql_sim.genero,Id_Genero',
            'pensionary' =>  'required',
            'email'   =>  'email',
            'phone'  =>  'nullable|numeric|digits:7',
            'mobile'  =>  'required|numeric|digits:10',
            'country_id'   =>  'required|numeric|exists:mysql_ldap.countries,id',
            'state_id'   =>  'required|numeric|exists:mysql_ldap.states,id',
            'city_id' =>  'required|numeric|exists:mysql_ldap.cities,id',
            'locality_id'    =>  'required|numeric|exists:mysql_parks.localidad,Id_Localidad',
            'address'   =>  'required|string|max:120',
            'stratum'   =>  'required|numeric|between:0,6',
            'cade_id'   =>  'required|numeric|exists:mysql_passport.tbl_supercades,i_pk_id',
            'interest_id'   =>  'required|numeric|exists:mysql_passport.tbl_actividades_interes,i_pk_id',
            'eps_id'   =>  'required|numeric|exists:mysql_passport.tbl_eps,i_pk_id',
            'observations'   =>  'nullable|max:2500',
            'question_1'   =>  'required',
            'question_2'   =>  'required',
            'question_3'   =>  'required',
            'question_4'   =>  'required',
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
            'document_type_id' =>  __('passport.validations.document_type_id'),
            'document'  =>  __('passport.validations.document'),
            'first_name' =>  __('passport.validations.first_name'),
            'middle_name'   =>  __('passport.validations.middle_name'),
            'first_last_name'    =>  __('passport.validations.first_last_name'),
            'second_last_name'    =>  __('passport.validations.second_last_name'),
            'birthdate'   =>  __('passport.validations.birthdate'),
            'sex_id'  =>  __('passport.validations.sex_id'),
            'file' =>  __('passport.validations.file'),
            'pensionary' =>  __('passport.validations.pensionary'),
            'email'   =>  __('passport.validations.email'),
            'phone'  =>  __('passport.validations.phone'),
            'mobile'  =>  __('passport.validations.mobile'),
            'country_id'   =>  __('passport.validations.country_id'),
            'state_id'   =>  __('passport.validations.state_id'),
            'city_id' =>  __('passport.validations.city_id'),
            'locality_id'    =>  __('passport.validations.locality_id'),
            'upz_id'   =>  __('passport.validations.upz_id'),
            'neighborhood_id'   =>  __('passport.validations.neighborhood_id'),
            'address'   =>  __('passport.validations.address'),
            'stratum'   =>  __('passport.validations.stratum'),
            'interest_id'   =>  __('passport.validations.interest_id'),
            'eps_id'   =>  __('passport.validations.eps_id'),
            'cade_id'   =>  'supercade',
            'observations'   =>  __('passport.validations.observations'),
            'question_1'   =>  __('passport.validations.question_1'),
            'question_2'   =>  __('passport.validations.question_2'),
            'question_3'   =>  __('passport.validations.question_3'),
            'question_4'   =>  __('passport.validations.question_4'),
        ];
    }
}
