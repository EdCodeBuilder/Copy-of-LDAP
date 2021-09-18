<?php


namespace App\Modules\Parks\src\Request;


use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $permission = toLower($this->getMethod()) == 'delete'
            ? Roles::can(User::class,'destroy_or_manage', true)
            : Roles::can(User::class,'create_or_manage', true);
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
            'roles'  => 'required|array',
        ];
    }
}
