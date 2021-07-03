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
        'users'     =>  'Users',
        'activities'    =>  'Hobbies',
        'eps'           =>  'Health Companies',
        'dashboard'     =>  'Dashboard',
        'card'          =>  'Card',
        'companies'     =>  'Companies',
        'portfolio'     =>  'Portfolio',
        'faq'     =>  'FAQ',
        'audit'     =>  'Audit',
    ],

    'classes' => [
        "App\Modules\Passport\src\Models\Agreements" => 'Services',
        "App\Modules\Passport\src\Models\Card" => 'Cards',
        "App\Modules\Passport\src\Models\Comment"  => 'Comments',
        "App\Modules\Passport\src\Models\PassportConfig"  => 'Image Passport Settings',
        "App\Modules\Passport\src\Models\Company" => 'Company',
        "App\Modules\Passport\src\Models\Dashboard" => 'Dashboard',
        "App\Modules\Passport\src\Models\Image" => 'Portfolio Image',
        "App\Modules\Passport\src\Models\Eps"    => 'EPS',
        "App\Modules\Passport\src\Models\Faq"    => 'FAQ',
        "App\Modules\Passport\src\Models\Hobby"    => 'Hobbies',
        "App\Modules\Passport\src\Models\Passport"   => 'New Passport',
        "App\Modules\Passport\src\Models\Type"    => 'User type',
        "App\Modules\Passport\src\Models\User" => 'Passport User',
    ]
];
