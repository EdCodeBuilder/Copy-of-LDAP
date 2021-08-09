<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRateRequest extends FormRequest
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
            'rate'  =>  'required|numeric|between:1,5'
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
            'rate' =>  __('passport.validations.rate'),
        ];
    }
}
