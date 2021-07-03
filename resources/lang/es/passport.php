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
        'users'         =>  'Usuarios',
        'activities'    =>  'Actividades de Interés',
        'eps'           =>  'EPS',
        'dashboard'     =>  'Tablero',
        'card'          =>  'Tarjeta',
        'companies'     =>  'Empresas',
        'portfolio'     =>  'Servicios',
        'faq'           =>  'Preguntas Frecuentes',
        'audit'         =>  'Auditoria',
    ],

    'classes' => [
        "App\Modules\Passport\src\Models\Agreements" => 'Servicios',
        "App\Modules\Passport\src\Models\Card" => 'Tarjetas',
        "App\Modules\Passport\src\Models\Comment"  => 'Comentarios',
        "App\Modules\Passport\src\Models\PassportConfig"  => 'Configuración Imagen Pasaporte',
        "App\Modules\Passport\src\Models\Company" => 'Empresas',
        "App\Modules\Passport\src\Models\Dashboard" => 'Dashboard',
        "App\Modules\Passport\src\Models\Image" => 'Imágen de Servicios',
        "App\Modules\Passport\src\Models\Eps"    => 'EPS',
        "App\Modules\Passport\src\Models\Faq"    => 'Preguntas Frecuentes',
        "App\Modules\Passport\src\Models\Hobby"    => 'Actividades de Interés',
        "App\Modules\Passport\src\Models\Passport"   => 'Pasaporte Nuevo',
        "App\Modules\Passport\src\Models\Type"    => 'Tipo de Persona',
        "App\Modules\Passport\src\Models\User" => 'Usuarios Pasaporte',
    ]
];
