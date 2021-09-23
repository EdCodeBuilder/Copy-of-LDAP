<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Scale;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre de la escala del parque con máximo 50 caracteres. Example: ZONAL
 * @bodyParam description string required Descripción de la escala del parque con máximo 5000 caracteres. Example: Lorem ipsum dolor sit amet.
 */
class ScaleRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Scale::class, $action), Scale::class) ||
            auth('api')->user()->can(Roles::can(Scale::class, 'manage'), Scale::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|max:50',
            'description'   => 'nullable|string|max:5000',
        ];
    }
}
