<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\StageType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del tipo de escenario, máximo 191 caracteres. Example: CEFE
 */
class StageTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $method = toLower($this->getMethod());
        $action = in_array($method, ['put', 'patch']) ? 'update' : 'create';
        return auth('api')->user()->can(Roles::can(StageType::class, $action), StageType::class) ||
            auth('api')->user()->can(Roles::can(StageType::class, 'manage'), StageType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
        ];
    }
}
