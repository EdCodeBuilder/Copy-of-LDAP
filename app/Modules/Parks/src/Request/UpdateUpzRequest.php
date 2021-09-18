<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Upz;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUpzRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->can(Roles::can(Upz::class, 'update'), Upz::class) ||
            auth('api')->user()->can(Roles::can(Upz::class, 'manage'), Upz::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $locality = new Location();
        $upz = new Upz();
        return [
            'name'      => 'required|string|max:50',
            'upz_code'  =>  "required|max:20|unique:{$upz->getConnectionName()}.{$upz->getTable()},cod_upz,".$this->route('upz')->id.",{$upz->getKeyName()}",
            'locality_id'   =>  "required|numeric|exists:{$locality->getConnectionName()}.{$locality->getTable()},{$locality->getKeyName()}",
        ];
    }
}
