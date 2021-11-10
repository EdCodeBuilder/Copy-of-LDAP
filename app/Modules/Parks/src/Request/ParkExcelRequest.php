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
 * @queryParam location Arreglo de ids o id de la localidad. No-example
 * @queryParam upz Arreglo de códigos de UPZ o código de la UPZ. No-example
 * @queryParam neighborhood Arreglo de ids o id del barrio del parque. No-example
 * @queryParam certified Parques que están certificados o no Ejemplo: certified, not_certified. No-example
 * @queryParam admin Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin. No-example
 * @queryParam enclosure Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno. No-example
 * @queryParam park_type Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3]. No-example
 */
class ParkExcelRequest extends FormRequest
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
            'location'   => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $values = is_array($value) ? $value : explode(',', $value);
                        if (!count($values) > 0 && !Location::query()->whereKey($value)->count() > 0) {
                            $fail(__('validation.exists', ['attribute' => $attribute]));
                        }
                    }
                },
            ],
            'upz'        => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $values = is_array($value) ? $value : explode(',', $value);
                        if (!count($values) > 0 && !Upz::query()->whereKey($value)->count() > 0) {
                            $fail(__('validation.exists', ['attribute' => $attribute]));
                        }
                    }
                },
            ],
            'neighborhood'  => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $values = is_array($value) ? $value : explode(',', $value);
                        if (!count($values) > 0 && !Neighborhood::query()->whereKey($value)->count() > 0) {
                            $fail(__('validation.exists', ['attribute' => $attribute]));
                        }
                    }
                },
            ],
            'certified'     => [
                'nullable',
                'string',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $items = ['certified', 'not_certified'];
                        if (!in_array(toLower($value), $items)) {
                            $fail("El campo $attribute debe contener alguno de los siguiente valores: certified, not_certified");
                        }
                    }
                },
            ],
            'admin'     => [
                'nullable',
                'string',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $items = ['admin', 'is_not_admin'];
                        if (!in_array(toLower($value), $items)) {
                            $fail("El campo $attribute debe contener alguno de los siguiente valores: admin, is_not_admin");
                        }
                    }
                },
            ],
            'enclosure'     => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!is_null($value) ) {
                        $values = is_array($value) ? $value : explode(',', $value);
                        if (count($values) > 0) {
                            if (!count($values) > 0 && !Enclosure::query()->whereIn('Cerramiento', $value)->count() > 0) {
                                $fail(__('validation.exists', ['attribute' => $attribute]));
                            }
                        }
                    }
                },
            ],
            'park_type'       => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!is_null($value)) {
                        $values = is_array($value) ? $value : explode(',', $value);
                        if (count($values) > 0) {
                            if (!count($values) > 0 && !Scale::query()->whereKey($value)->count() > 0) {
                                $fail(__('validation.exists', ['attribute' => $attribute]));
                            }
                        }
                    }
                },
            ]
        ];
    }
}
