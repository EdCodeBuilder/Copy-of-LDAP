<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

class ParkFinderRequest extends FormRequest
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
            'query'        => [ new ParkFinderRule() ],
            'locality_id'  => [ new ParkFinderRule() ],
            'upz_id'  => [ new ParkFinderRule() ],
            'type_id'      => [ new ParkFinderRule() ],
        ];
    }
}
