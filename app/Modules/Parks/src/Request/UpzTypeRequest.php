<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\UpzType;
use Illuminate\Foundation\Http\FormRequest;

class UpzTypeRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(UpzType::class, $action), UpzType::class) ||
            auth('api')->user()->can(Roles::can(UpzType::class, 'manage'), UpzType::class);
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
