<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class FindUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->hasAnyPermission(
            Roles::can(User::class, 'view_or_manage', true)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'  =>  'required|string|min:3|max:191',
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
            'username' =>  __('validation.attributes.username'),
        ];
    }
}
