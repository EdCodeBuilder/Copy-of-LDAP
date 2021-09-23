<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @queryParam query Código, nombre o dirección del parque. Example: 03-036
 * @queryParam locality_id Arreglo de ids o id de la localidad. No-example
 * @queryParam upz_id Arreglo de códigos de UPZ o código de la UPZ. No-example
 * @queryParam neighborhood_id Arreglo de ids o id del barrio del parque. No-example
 * @queryParam type_id Arreglo de ids o id de la escala del parque. No-example
 * @queryParam vigilance Parques que cuentan con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia. No-example
 * @queryParam enclosure Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno. No-example
 * @queryParam column Campo de ordenamiento Ejemplo: ?column[]=name. No-example
 * @queryParam order Orden de los resultados true para ascendente o false para descendente Ejemplo: ?order[]=true. No-example
 * @queryParam page La página a retornar Ejemplo: ?page=3. No-example
 * @queryParam per_page La cantidad de resultados a retornar Ejemplo: ?per_page=58. No-example
 */
class ParkFinderRequest extends FormRequest
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
        return [
            'query'         => 'nullable|string',
            'locality_id'   => [
                'nullable',
                function($attribute, $value, $fail) {
                 $values = is_array($value) ? $value : explode(',', $value);
                    if (!count($values) > 0 && !Location::query()->whereKey($value)->count() > 0) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'upz_id'        => [
                'nullable',
                function($attribute, $value, $fail) {
                 $values = is_array($value) ? $value : explode(',', $value);
                    if (!count($values) > 0 && !Upz::query()->whereKey($value)->count() > 0) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'neighborhood_id'  => [
                'nullable',
                function($attribute, $value, $fail) {
                 $values = is_array($value) ? $value : explode(',', $value);
                 if (!count($values) > 0 && !Neighborhood::query()->whereKey($value)->count() > 0) {
                     $fail(__('validation.exists', ['attribute' => $attribute]));
                 }
                },
            ],
            'vigilance'     => [
                'nullable',
                'string',
                function($attribute, $value, $fail) {
                    $items = ['sin vigilancia', 'con vigilancia'];
                    if (!in_array(toLower($value), $items)) {
                        $fail("El campo $attribute debe contener alguno de los siguiente valores: Sin vigilancia, Con vigilancia");
                    }
                },
            ],
            'enclosure'     => [
                'nullable',
                function($attribute, $value, $fail) {
                    $values = is_array($value) ? $value : explode(',', $value);
                    if (!count($values) > 0 && !Enclosure::query()->whereIn('Cerramiento', $value)->count() > 0) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'type_id'       => [
                'nullable',
                function($attribute, $value, $fail) {
                    $values = is_array($value) ? $value : explode(',', $value);
                    if (!count($values) > 0 && !Scale::query()->whereKey($value)->count() > 0) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ]
        ];
    }
}
