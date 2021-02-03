<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateParkRequest extends FormRequest
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
            'code'                  =>  'required|string|min:1|max:20|unique:mysql_parks.parque,Id_IDRD,'.$this->route('park')->Id,
            'name'                  =>  'required|string|min:3|max:200',
            'address'               =>  'required|string|min:3|max:120',
            'stratum'               =>  'required|numeric|min:1|max:10',
            'locality_id'           =>  'required|numeric|exists:mysql_parks.localidad,Id_Localidad',
            'upz_code'              =>  'required|numeric|exists:mysql_parks.upz,cod_upz',
            'neighborhood_id'       =>  'required|numeric|exists:mysql_parks.Barrios,IdBarrio',
            'urbanization'          =>  'required|string|min:3',
            'latitude'              =>  ['nullable','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude'             =>  ['nullable','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'area_hectare'          =>  'nullable|numeric',
            'area'                  =>  'nullable|numeric',
            'grey_area'             =>  'nullable|numeric',
            'green_area'            =>  'nullable|numeric',
            'capacity'              =>  'nullable|numeric',
            'children_population'   =>  'nullable|numeric',
            'youth_population'      =>  'nullable|numeric',
            'older_population'      =>  'nullable|numeric',
            'enclosure'             =>  'nullable|string|min:3|max:30',
            'households'            =>  'nullable|numeric',
            'walking_trails'        =>  'nullable|numeric',
            'walking_trails_status' =>  'nullable|string|min:3|max:30',
            'access_roads'          =>  'nullable|string|max:20',
            'access_roads_status'   =>  'nullable|string|min:3|max:30',
            'zone_type'             =>  'nullable|string|min:3|max:30',
            'scale_id'              =>  'nullable|numeric|exists:mysql_parks.tipo,Id_Tipo',
            'concern'               =>  'nullable|string|min:3|max:500',
            'visited_at'            =>  'nullable|date|date_format:Y-m-d',
            'general_status'        =>  'nullable|string|min:3|max:30',
            'stage_type_id'         =>  'nullable|numeric|exists:mysql_parks.TipoEscenario,id',
            'status_id'             =>  'nullable|numeric|exists:mysql_parks.estado,Id_Estado',
            'admin'                 =>  'nullable|string|min:3|max:50',
            'phone'                 =>  'nullable|numeric',
            'email'                 =>  'nullable|email',
            'admin_name'            =>  'nullable|string|min:3|max:500',
            'vigilance'             =>  'nullable|string',
            'received'              =>  'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code'                  =>  __('parks.attributes.code'),
            'name'                  =>  __('parks.attributes.name'),
            'address'               =>  __('parks.attributes.address'),
            'stratum'               =>  __('parks.attributes.stratum'),
            'locality_id'           =>  __('parks.attributes.locality_id'),
            'upz_code'              =>  __('parks.attributes.upz_code'),
            'neighborhood_id'       =>  __('parks.attributes.neighborhood_id'),
            'urbanization'          =>  __('parks.attributes.urbanization'),
            'latitude'              =>  __('parks.attributes.latitude'),
            'longitude'             =>  __('parks.attributes.longitude'),
            'area_hectare'          =>  __('parks.attributes.area_hectare'),
            'area'                  =>  __('parks.attributes.area'),
            'grey_area'             =>  __('parks.attributes.grey_area'),
            'green_area'            =>  __('parks.attributes.green_area'),
            'capacity'              =>  __('parks.attributes.capacity'),
            'children_population'   =>  __('parks.attributes.children_population'),
            'youth_population'      =>  __('parks.attributes.youth_population'),
            'older_population'      =>  __('parks.attributes.older_population'),
            'enclosure'             =>  __('parks.attributes.enclosure'),
            'households'            =>  __('parks.attributes.households'),
            'walking_trails'        =>  __('parks.attributes.walking_trails'),
            'walking_trails_status' =>  __('parks.attributes.walking_trails_status'),
            'access_roads'          =>  __('parks.attributes.access_roads'),
            'access_roads_status'   =>  __('parks.attributes.access_roads_status'),
            'zone_type'             =>  __('parks.attributes.zone_type'),
            'scale_id'              =>  __('parks.attributes.scale_id'),
            'concern'               =>  __('parks.attributes.concern'),
            'visited_at'            =>  __('parks.attributes.visited_at'),
            'general_status'        =>  __('parks.attributes.general_status'),
            'stage_type_id'         =>  __('parks.attributes.stage_type_id'),
            'status_id'             =>  __('parks.attributes.status_id'),
            'admin'                 =>  __('parks.attributes.admin'),
            'phone'                 =>  __('parks.attributes.phone'),
            'email'                 =>  __('parks.attributes.email'),
            'admin_name'            =>  __('parks.attributes.admin_name'),
            'vigilance'             =>  __('parks.attributes.vigilance'),
            'received'              =>  __('parks.attributes.received'),
        ];
    }
}
