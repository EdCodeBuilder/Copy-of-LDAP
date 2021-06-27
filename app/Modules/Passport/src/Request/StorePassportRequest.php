<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePassportRequest extends FormRequest
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
            'document_type_id' =>  'required|numeric|exists:mysql_sim.tipo_documento,Id_TipoDocumento',
            'document'  =>  [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $user =  User::where('Cedula', $value)->first();
                    if (isset($user->Id_Persona) && $user->passport()->count() > 0) {
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
            'email'   =>  'required|email',
            'phone'  =>  'nullable|numeric|digits:7',
            'mobile'  =>  'required|numeric|digits:10',
            'country_id'   =>  'required|numeric|exists:mysql_ldap.countries,id',
            'state_id'   =>  'required|numeric|exists:mysql_ldap.states,id',
            'city_id' =>  'required|numeric|exists:mysql_ldap.cities,id',
            'locality_id'    =>  'required|numeric|exists:mysql_parks.localidad,Id_Localidad',
            'upz_id'   =>  'required|numeric|exists:mysql_parks.upz,Id_Upz',
            'neighborhood_id'   =>  'required|numeric|exists:mysql_parks.Barrios,IdBarrio',
            'address'   =>  'required|string|max:120',
            'stratum'   =>  'required|numeric|between:0,6',
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
            'document_type_id' =>  'tipo de documento',
            'document'  =>  'documento',
            'first_name' =>  'primer nombre',
            'middle_name'   =>  'segundo nombre',
            'first_last_name'    =>  'primer apellido',
            'second_last_name'    =>  'segundo apellido',
            'birthdate'   =>  'fecha de nacimiento',
            'sex_id'  =>  'sexo',
            'pensionary' =>  'pensionado',
            'email'   =>  'correo electrónico personal',
            'phone'  =>  'teléfono',
            'mobile'  =>  'celular',
            'country_id'   =>  'país de nacimiento',
            'state_id'   =>  'departamento de nacimiento',
            'city_id' =>  'ciudad de nacimiento',
            'locality_id'    =>  'localidad',
            'upz_id'   =>  'upz',
            'neighborhood_id'   =>  'barrio',
            'address'   =>  'dirección',
            'stratum'   =>  'estrato',
            'interest_id'   =>  'actividades de interés',
            'eps_id'   =>  'eps',
            'observations'   =>  'observaciones',
            'question_1'   =>  'pregunta 1',
            'question_2'   =>  'pregunta 2',
            'question_3'   =>  'pregunta 3',
            'question_4'   =>  'pregunta 4',
        ];
    }
}
