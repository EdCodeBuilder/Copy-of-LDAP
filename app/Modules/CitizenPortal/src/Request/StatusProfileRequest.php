<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

class StatusProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->isAn(...Roles::onlyAdmin()) ||
            auth('api')->user()->can(Roles::can(Profile::class, 'status'), $this->route('profile'));
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
            'status_id' =>  __('citizen.validations.status'),
        ];
    }
}
