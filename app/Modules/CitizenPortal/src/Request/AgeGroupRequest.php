<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class AgeGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(...Roles::onlyAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             =>  'required|string|min:3|max:50',
            'min'      =>  'required|numeric|between:0,100|lte:max_age',
            'max'      =>  'required|numeric|between:0,100|gte:min_age',
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
            'name' =>  __('citizen.validations.name'),
            'min' =>  __('citizen.validations.min_age'),
            'max' =>  __('citizen.validations.max_age'),
        ];
    }
}
