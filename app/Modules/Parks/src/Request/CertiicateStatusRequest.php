<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Certified;
use App\Modules\Parks\src\Models\Enclosure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Nombre del estado de certificación, máximo 30 caracteres. Example: Investigado
 */
class CertiicateStatusRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Certified::class, $action), Certified::class) ||
            auth('api')->user()->can(Roles::can(Certified::class, 'manage'), Certified::class);
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
