<?php

namespace App\Modules\Parks\src\Request;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Models\StageType;
use App\Modules\Parks\src\Models\Status;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Models\Vocation;
use App\Modules\Parks\src\Rules\ParkFinderRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam code string required Código del parque. Example: 03-036
 * @bodyParam name  string required Nombre del parque. Example: LAS CRUCES
 * @bodyParam address string required Dirección del parque. Example: CARRERA 5A #1- 90
 * @bodyParam stratum int required Estrato del parque. Example: 2
 * @bodyParam locality_id int required Id de la localidad del parque.
 * @bodyParam upz_code string required Código de la UPZ del parque.
 * @bodyParam neighborhood_id int required Id del barrio del parque.
 * @bodyParam urbanization string required Nombre de la urbanización del parque. Example: PREDIOS PÚBLICOS NO CESIÓN
 * @bodyParam latitude string Latitud del parque. Example: 4.585764498926
 * @bodyParam longitude string Longitud del parque. Example: -74.0787936235177
 * @bodyParam area_hectare int Área en hectáreas del parque. Example: 1.21
 * @bodyParam area int Área del parque. Example: 5.79
 * @bodyParam grey_area int Área zona dura del parque. Example: 20
 * @bodyParam green_area int Área zona verde del parque Example: 30
 * @bodyParam capacity int Capacidad de personas en el parque. Example: 2367
 * @bodyParam children_population int Población infantil. Example: 33
 * @bodyParam youth_population int Población juvenil. Example: 34
 * @bodyParam older_population int Población mayor. Example: 32
 * @bodyParam enclosure string Tipo de cerramiento del parque. Example: Total
 * @bodyParam households int Cantidad de viviendas Example: 77
 * @bodyParam walking_trails int Cantidad de senderos. Example: 700
 * @bodyParam walking_trails_status string Estado de los senderos. Example: BUENO
 * @bodyParam access_roads int Cantidad de vías. Example: 53
 * @bodyParam access_roads_status  string Estado de las vías. Example: REGULAR
 * @bodyParam zone_type string Tipo de Zona Example: RESIDENCIAL/COMERCIAL
 * @bodyParam scale_id int Id de la escala del parque. Example: 3
 * @bodyParam concern string Competencia/Regulación del parque. Example: IDRD
 * @bodyParam visited_at date Fecha de última visita al parque en formato AAAA-MM-DD. Example: 2021-09-17
 * @bodyParam general_status string Estado general del parque. Example: BUENO
 * @bodyParam stage_type_id int Id del tipo de escenario Example: 1
 * @bodyParam status_id int Id de estado del parque. Example: 2
 * @bodyParam admin string Entidad que administra el parque. Example: Junta de Acción Comunal/IDRD
 * @bodyParam phone string Números telefónicos del parque separados por coma, Ejemplo: 2800004, 6605300. Example: 2800004
 * @bodyParam email string Correo electrónico del parque Example: lascruces@idrd.gov.co
 * @bodyParam admin_name string Nombre del administrador del parque. Example: Jhon Doe
 * @bodyParam vigilance string Cuenta o no con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia. Example: Con Vigilancia
 * @bodyParam received string El parque es recibido por el IDRD, Ejemplo: Si, No. Example: Si
 * @bodyParam vocation_id int Id de la vocación del parque. Example: 2
 */
class UpdateParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->can(Roles::can(Park::class, 'update'), $this->route('park')) ||
            auth('api')->user()->can(Roles::can(Park::class, 'manage'), Park::class);
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
        $neighborhood = new Neighborhood();
        $park = new Park();
        $scale = new Scale();
        $stage = new StageType();
        $status = new Status();
        $vocation = new Vocation();
        return [
            'code'                  =>  "required|string|min:1|max:20|unique:{$park->getConnectionName()}.{$park->getTable()},Id_IDRD,{$this->route('park')->Id}",
            'name'                  =>  'required|string|min:3|max:200',
            'address'               =>  'required|string|min:3|max:120',
            'stratum'               =>  'required|numeric|min:1|max:10',
            'locality_id'           =>  "required|numeric|exists:{$locality->getConnectionName()}.{$locality->getTable()},{$locality->getKeyName()}",
            'upz_code'              =>  "required|exists:{$upz->getConnectionName()}.{$upz->getTable()},cod_upz",
            'neighborhood_id'       =>  "required|numeric|exists:{$neighborhood->getConnectionName()}.{$neighborhood->getTable()},{$neighborhood->getKeyName()}",
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
            'scale_id'              =>  "nullable|numeric|exists:{$scale->getConnectionName()}.{$scale->getTable()},{$scale->getKeyName()}",
            'concern'               =>  'nullable|string|min:3|max:500',
            'visited_at'            =>  'nullable|date|date_format:Y-m-d',
            'general_status'        =>  'nullable|string|min:3|max:30',
            'stage_type_id'         =>  "nullable|numeric|exists:{$stage->getConnectionName()}.{$stage->getTable()},{$stage->getKeyName()}",
            'status_id'             =>  "nullable|numeric|exists:{$status->getConnectionName()}.{$status->getTable()},{$status->getKeyName()}",
            'admin'                 =>  'nullable|string|min:3|max:50',
            'phone'                 =>  'nullable|numeric',
            'email'                 =>  'nullable|email',
            'admin_name'            =>  'nullable|string|min:3|max:500',
            'vigilance'             =>  'nullable|string',
            'received'              =>  'nullable|string',
            'vocation_id'           =>  "nullable|numeric|exists:{$vocation->getConnectionName()}.{$vocation->getTable()},{$vocation->getKeyName()}",
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
            'vocation_id'           =>  __('parks.attributes.vocation_id'),
        ];
    }
}
