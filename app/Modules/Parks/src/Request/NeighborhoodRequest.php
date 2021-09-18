<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Upz;
use Illuminate\Foundation\Http\FormRequest;

class NeighborhoodRequest extends FormRequest
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
        return auth('api')->user()->can(Roles::can(Neighborhood::class, $action), Neighborhood::class) ||
            auth('api')->user()->can(Roles::can(Neighborhood::class, 'manage'), Neighborhood::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $neighborhood = new Neighborhood();
        $upz = new Upz();
        $method = toLower($this->getMethod());
        $id = $neighborhood->getKeyName();
        $action = in_array($method, ['put', 'patch']) ? ",{$this->route('neighborhood')->{$id}},$id" : '';
        return [
            'name'          => 'required|string|max:500',
            'neighborhood_code'      =>  "nullable|unique:{$neighborhood->getConnectionName()}.{$neighborhood->getTable()},CodBarrio".$action,
            'upz_code'      =>  "required|exists:{$upz->getConnectionName()}.{$upz->getTable()},cod_upz",
        ];
    }
}
