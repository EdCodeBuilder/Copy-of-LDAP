<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class MassiveScheduleRequest extends FormRequest
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
            'file'             =>  'required|file|mimes:xlsx',
            'force'           =>  'required|boolean',
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
            'file' =>  __('citizen.validations.file'),
        ];
    }
}
