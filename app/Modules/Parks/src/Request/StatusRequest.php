<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del estado, máximo 30 caracteres. Example: BUENO
 */
class StatusRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $method = toLower($this->getMethod());
        $action = in_array($method, ['put', 'patch']) ? 'update' : 'create';
        return auth('api')->user()->can(Roles::can(Status::class, $action), Status::class) ||
            auth('api')->user()->can(Roles::can(Status::class, 'manage'), Status::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|max:30',
        ];
    }
}
