<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;

class AssignorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(Roles::ROLE_ASSIGNOR, ...Roles::onlyAdmin()) ||
            auth('api')->user()->can(Roles::can(Profile::class, 'validator'), Profile::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = new User();
        return [
            'validator_id'             =>  [
                'required',
                'numeric',
                "exists:{$user->getConnectionName()}.{$user->getTable()},{$user->getKeyName()}"
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
            'validator_id' =>  __('citizen.validations.validator'),
        ];
    }
}
