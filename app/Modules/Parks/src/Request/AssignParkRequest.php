<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignParkRequest extends FormRequest
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
            'user_id'         => 'required|numeric|exists:mysql_ldap.users,id',
            'locality_id'     => 'required_if:type_assignment,locality|nullable|numeric|exists:mysql_parks.localidad,Id_Localidad',
            'upz_code'        => 'required_if:type_assignment,upz|nullable|exists:mysql_parks.upz,cod_upz',
            'neighborhood_id' => 'required_if:type_assignment,neighborhood|nullable|numeric|exists:mysql_parks.Barrios,IdBarrio',
            'type_assignment' => 'required',
            'park_id'         => 'nullable|array',
            'park_id.*'       => 'required|numeric|exists:mysql_parks.parque,Id',
        ];
    }

    public function attributes()
    {
        return [
            'user_id'        => 'usuario',
            'park_id'        => 'parque',
            'locality_id'        => 'localidad',
            'upz_code'        => 'upz',
            'neighborhood_id'        => 'barrio',
        ];
    }
}
