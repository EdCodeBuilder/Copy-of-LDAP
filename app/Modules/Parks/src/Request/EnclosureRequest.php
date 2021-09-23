<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del tipo de cerramiento, máximo 30 caracteres. Example: Total
 */
class EnclosureRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Enclosure::class, $action), Enclosure::class) ||
            auth('api')->user()->can(Roles::can(Enclosure::class, 'manage'), Enclosure::class);
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
