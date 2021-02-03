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
        'users'     =>  'Usuarios',
        'dashboard' =>  'Tablero',
        'finder'    =>  'Buscador de Parques',
        'manage'     => 'Gestión Parques',
        'parks'     =>  'Crear Parque',
        'locality'  =>  'Gestionar Localidades',
        'map'       =>  'Mapa',
    ],
    'handler'    => [
        'park_does_not_exist'   => 'No se encontró ningún parque relacionado al código o id :code',
    ],
    'attributes'    =>  [
        'code'                  =>  'código del parque',
        'name'                  =>  'nombre del parque',
        'address'               =>  'dirección del parque',
        'stratum'               =>  'estrato',
        'locality_id'           =>  'localidad',
        'upz_code'              =>  'upz',
        'neighborhood_id'       =>  'barrio',
        'urbanization'          =>  'urbanización',
        'latitude'              =>  'latitud',
        'longitude'             =>  'longitude',
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
        'stage_type_id'         =>  'tupo escenario',
        'status_id'             =>  'estado',
        'admin'                 =>  'adminitrado por',
        'phone'                 =>  'teéfono',
        'email'                 =>  'correo permisos',
        'admin_name'            =>  'nombre administrador',
        'vigilance'             =>  'vigilancia',
        'received'              =>  'recibido IDRD',
    ],
];
