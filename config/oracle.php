<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_ORACLE_TNS', ''),
        'host'           => env('DB_ORACLE_HOST', ''),
        'port'           => env('DB_ORACLE_PORT', '1521'),
        'service_name'   => env('DB_ORACLE_SERVICE_NAME', ''),
        'database'       => env('DB_ORACLE_DATABASE', ''),
        'username'       => env('DB_ORACLE_USERNAME', ''),
        'password'       => env('DB_ORACLE_PASSWORD', ''),
        'charset'        => env('DB_ORACLE_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_ORACLE_PREFIX', ''),
        'prefix_schema'  => env('DB_ORACLE_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_ORACLE_EDITION', 'ora$base'),
        'server_version' => env('DB_ORACLE_SERVER_VERSION', '11g'),
    ],
];
