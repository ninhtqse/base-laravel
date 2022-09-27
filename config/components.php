<?php

return [
    'namespaces' => [
        'Api' => base_path() . DIRECTORY_SEPARATOR . 'api',
        'App' => base_path() . DIRECTORY_SEPARATOR . 'app',
        'Infrastructure' => base_path() . DIRECTORY_SEPARATOR . 'infrastructure'
    ],

    'protection_middleware' => [
        'auth_api:api'
    ],

    'protection_basic_middleware' => [
        'auth.basic.once'
    ],

    'resource_namespace' => 'resources',

    'language_folder_name' => 'lang',

    'view_folder_name' => 'views',

    'prefix' => ''
];
