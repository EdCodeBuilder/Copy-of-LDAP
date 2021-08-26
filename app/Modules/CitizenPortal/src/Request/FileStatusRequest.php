<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

class FileStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(Roles::ROLE_VALIDATOR, ...Roles::onlyAdmin()) ||
            auth('api')->user()->can(Roles::can(File::class, 'status'), File::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $status = new Status();
        return [
            'observation'   => 'required|min:3|max:2500',
            'status_id'             =>  [
                'required',
                'numeric',
                "exists:{$status->getConnectionName()}.{$status->getTable()},{$status->getKeyName()}"
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
