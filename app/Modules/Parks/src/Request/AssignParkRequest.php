<?php

namespace App\Modules\Parks\src\Request;

use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam user_id int required Id del usuario al que se asignará el parque. Example: 1
 * @bodyParam locality_id int Es requerido si el tipo de asignamiento es "locality". No-example
 * @bodyParam upz_code int Es requerido si el tipo de asignamiento es "upz". No-example
 * @bodyParam neighborhood_id int Es requerido si el tipo de asignamiento es "neighborhood". No-example
 * @bodyParam type_assignment string required Tipo de asignamiento Puede ser: locality, upz, neighborhood o manual. Example: manual
 * @bodyParam park_id.* int Es requerido si el tipo de asignamiento es "manual" y debe contener Id de parques. Example: [9, 2938, 374]
 */
class AssignParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->can(Roles::can(Park::class, 'manage'), Park::class);
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
