<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\Parks\src\Models\Park;
use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $permission = toLower($this->getMethod()) == 'post'
            ? Roles::can(Status::class,'create_or_manage', true)
            : Roles::can(Status::class,'update_or_manage', true);
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
            'name'  =>  'required|string|min:3|max:191',
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
            'name'   =>  __('citizen.validations.name'),
        ];
    }
}
