<?php


namespace App\Modules\Passport\src\Request;


use App\Modules\Passport\src\Constants\Roles;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(...Roles::all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company'      =>  'required|string|min:3|max:191',
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
            'company' =>  __('passport.validations.company'),
        ];
    }
}
