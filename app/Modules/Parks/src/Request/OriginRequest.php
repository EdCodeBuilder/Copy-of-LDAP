<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Origin;
use App\Modules\Parks\src\Models\Park;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam paragraph_1 string required Texto asociado a la historia del parque, máximo 2500 caracteres.
 * @bodyParam paragraph_2 string Texto asociado a la historia del parque, máximo 2500 caracteres. No-example
 * @bodyParam image_1 file Imágen asociada al parque (png, jpg). No-example
 * @bodyParam image_2 file Imágen asociada al parque (png, jpg). No-example
 * @bodyParam image_3 file Imágen asociada al parque (png, jpg). No-example
 * @bodyParam image_4 file Imágen asociada al parque (png, jpg). No-example
 * @bodyParam image_5 file Imágen asociada al parque (png, jpg). No-example
 * @bodyParam image_6 file Imágen asociada al parque (png, jpg). No-example
 */
class OriginRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Origin::class, $action), Origin::class) ||
            auth('api')->user()->can(Roles::can(Origin::class, 'manage'), Origin::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paragraph_1'   => 'required|string|min:3|max:2500',
            'paragraph_2'   => 'nullable|string|min:3|max:2500',
            'image_1'          => 'nullable|file|image',
            'image_2'          => 'nullable|file|image',
            'image_3'          => 'nullable|file|image',
            'image_4'          => 'nullable|file|image',
            'image_5'          => 'nullable|file|image',
            'image_6'          => 'nullable|file|image',
        ];
    }
}
