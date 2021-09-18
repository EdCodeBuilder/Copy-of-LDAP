<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\AgeGroup;
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
        $permission = toLower($this->getMethod()) == 'post'
            ? Roles::can(AgeGroup::class,'create_or_manage', true)
            : Roles::can(AgeGroup::class,'update_or_manage', true);
        return auth('api')->user()->hasAnyPermission($permission);
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
            'min'      =>  'required|numeric|between:0,100|lte:max',
            'max'      =>  'required|numeric|between:0,100|gte:min',
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
