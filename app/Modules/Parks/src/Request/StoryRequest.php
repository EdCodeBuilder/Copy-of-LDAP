<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Story;
use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam title string required Título del tado de interés con máximo 191 caracteres. Example: CONDICIONES DE USO
 * @bodyParam text string required Texto descriptivo con máximo 2500 caracteres.
 */
class StoryRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Story::class, $action), Story::class) ||
            auth('api')->user()->can(Roles::can(Story::class, 'manage'), Story::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'  => 'required|string|min:3|max:191',
            'text'   => 'required|string|min:3|max:2500',
        ];
    }
}
