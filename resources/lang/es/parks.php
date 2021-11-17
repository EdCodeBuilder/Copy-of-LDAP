<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Park Modules Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during park module access for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'menu'  =>  [
        'roles'     =>  'Roles y Permisos',
        'users'     =>  'Usuarios',
        'dashboard' =>  'Tablero',
        'finder'    =>  'Buscador de Parques',
        'manage'     => 'Gestión Parques',
        'parks'     =>  'Crear Parque',
        'owned'     =>  'Mis Parques',
        'locality'  =>  'Gestionar Localidades',
        'enclosure' =>  'Gestionar Cerramientos',
        'scales'    =>  'Gestionar Escalas',
        'stages'    =>  'Gestionar Escenarios',
        'vocation'  =>  'Gestionar Vocaciones',
        'status'    =>  'Gestionar Estados',
        'upz_types' =>  'Tipos de UPZ',
        'certified' =>  'Estado Certificados',
        'map'       =>  'Mapa',
        'audit'     =>  'Auditoria',
    ],
    'handler'    => [
        'park_does_not_exist'   => 'No se encontró ningún parque relacionado al código o id :code',
    ],
    'excel'     => [
        'title' =>  'PARQUES',
        'generated_by' =>  'Generado por',
        'created_at'    => 'Fecha de creación',
    ],
    'attributes'    =>  [
        'code'                  =>  'código del parque',
        'name'                  =>  'nombre del parque',
        'address'               =>  'dirección del parque',
        'stratum'               =>  'estrato',
        'locality_id'           =>  'localidad',
        'upz_code'              =>  'upz',
        'upz'                   =>  'upz',
        'neighborhood_id'       =>  'barrio',
        'urbanization'          =>  'urbanización',
        'latitude'              =>  'latitud',
        'longitude'             =>  'longitud',
        'area_hectare'          =>  'área en hectáreas',
        'area'                  =>  'área',
        'grey_area'             =>  'área zona dura',
        'green_area'            =>  'área zona verde',
        'capacity'              =>  'aforo',
        'children_population'   =>  'población infantil',
        'youth_population'      =>  'población juvenil',
        'older_population'      =>  'población mayor',
        'enclosure'             =>  'tipo cerramiento',
        'households'            =>  'viviendas',
        'walking_trails'        =>  'senderos',
        'walking_trails_status' =>  'estado senderos',
        'access_roads'          =>  'vías de acceso',
        'access_roads_status'   =>  'estado vías de acceso',
        'zone_type'             =>  'tipo de zona',
        'scale_id'              =>  'escala del parque',
        'concern'               =>  'competencia/regulación',
        'visited_at'            =>  'fecha visita',
        'general_status'        =>  'estado general',
        'stage_type_id'         =>  'tipo escenario',
        'status_id'             =>  'estado',
        'certified_status'       =>  'estado certificado',
        'admin'                 =>  'adminitrado por',
        'phone'                 =>  'teléfono',
        'email'                 =>  'correo permisos',
        'admin_name'            =>  'nombre administrador',
        'vigilance'             =>  'vigilancia',
        'received'              =>  'recibido IDRD',
        'compete'               =>  'compete IDRD',
        'vocation_id'           =>  'vocación',
        'render'                =>  'diagrama'
    ],
    'classes' => [
        "App\Modules\Parks\src\Models\AssignedPark" => '',
        "App\Modules\Parks\src\Models\EconomicUse"  => '',
        "App\Modules\Parks\src\Models\EconomicUsePark"  => '',
        "App\Modules\Parks\src\Models\EmergencyPlan"    => '',
        "App\Modules\Parks\src\Models\EmergencyPlanCategory"    => '',
        "App\Modules\Parks\src\Models\EmergencyPlanFile"    => '',
        "App\Modules\Parks\src\Models\Endowment"    => '',
        "App\Modules\Parks\src\Models\Equipment"    => '',
        "App\Modules\Parks\src\Models\Material" => '',
        "App\Modules\Parks\src\Models\ParkEndowment"    => '',
        "App\Modules\Parks\src\Models\Sector"   => '',

        "App\Modules\Parks\src\Models\Origin"    => 'Historia del Parque',
        "App\Modules\Parks\src\Models\Certified"    => 'Estado Certificado',
        "App\Modules\Parks\src\Models\Park" => 'Parques',
        "App\Modules\Parks\src\Models\Location" => 'Localidades',
        "App\Modules\Parks\src\Models\UpzType"  => 'Tipos de Upz',
        "App\Modules\Parks\src\Models\Upz"  => 'Upz',
        "App\Modules\Parks\src\Models\Neighborhood" => 'Barrios',
        "App\Modules\Parks\src\Models\Rupi" => 'Rupis',
        "App\Modules\Parks\src\Models\Scale"    => 'Escalas',
        "App\Modules\Parks\src\Models\Enclosure"    => 'Cerramientos',
        "App\Modules\Parks\src\Models\StageType"    => 'Tipos de Escenarios',
        "App\Modules\Parks\src\Models\Status"   => 'Estados',
        "App\Modules\Parks\src\Models\Story"    => 'Datos de interés',
        "App\Modules\Parks\src\Models\Vocation" => 'Vocaciones',
        "App\Models\Security\User" => 'Usuarios',
        "OwenIt\Auditing\Models\Audit" => 'Auditoría'
    ]
];
