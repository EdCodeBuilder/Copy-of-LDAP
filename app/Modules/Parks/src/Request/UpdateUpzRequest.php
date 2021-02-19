<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUpzRequest extends FormRequest
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
            'name'      => 'required|string|max:50',
            'upz_code'  =>  'required|max:20|unique:mysql_parks.upz,cod_upz,'.$this->route('upz')->id.',Id_Upz',
            'locality_id'   =>  'required|numeric|exists:mysql_parks.localidad,Id_Localidad',
        ];
    }
}
