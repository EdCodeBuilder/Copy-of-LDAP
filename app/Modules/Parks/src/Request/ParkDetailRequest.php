<?php

namespace App\Modules\Parks\src\Request;

use Illuminate\Foundation\Http\FormRequest;

class ParkDetailRequest extends FormRequest
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
            'code'  =>  'required|exist:mysql_parks.parque,Id_IDRD'
        ];
    }
}
