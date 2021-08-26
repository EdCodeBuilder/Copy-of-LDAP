<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\Parks\src\Models\Park;
use Illuminate\Foundation\Http\FormRequest;

class StageRequest extends FormRequest
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
        $park = new Park();
        return [
            'name'             =>  'required|string|min:3|max:191',
            'park_id'     =>  [
                'required',
                'numeric',
                "exists:{$park->getConnectionName()}.{$park->getTable()},{$park->getKeyName()}"
            ],
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
            'name'     =>  __('citizen.validations.name'),
            'park_id'  =>  __('citizen.validations.park'),
        ];
    }
}
