<?php

namespace App\Modules\Parks\src\Request;

use App\Models\Security\User;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = new User();
        $locality = new Location();
        $upz = new Upz();
        $neighborhood = new Neighborhood();
        $park = new Park();
        return [
            'user_id'         => "required|numeric|exists:{$user->getConnectionName()}.{$user->getTable()},{$user->getKeyName()}",
            'locality_id'     => "required_if:type_assignment,locality|nullable|numeric|exists:{$locality->getConnectionName()}.{$locality->getTable()},{$locality->getKeyName()}",
            'upz_code'        => "required_if:type_assignment,upz|nullable|exists:{$upz->getConnectionName()}.{$upz->getTable()},{$upz->getKeyName()}",
            'neighborhood_id' => "required_if:type_assignment,neighborhood|nullable|numeric|exists:{$neighborhood->getConnectionName()}.{$neighborhood->getTable()},{$neighborhood->getKeyName()}",
            'type_assignment' => 'required',
            'park_id'         => 'nullable|array',
            'park_id.*'       => "required|numeric|exists:{$park->getConnectionName()}.{$park->getTable()},{$park->getKeyName()}",
        ];
    }

    public function attributes()
    {
        return [
            'user_id'        => 'usuario',
            'park_id'        => 'parque',
            'locality_id'        => 'localidad',
            'upz_code'        => 'upz',
            'neighborhood_id'        => 'barrio',
        ];
    }
}
