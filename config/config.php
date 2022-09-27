<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Các tham số cấu hình cho ứng dụng
    |--------------------------------------------------------------------------
    |
    */

    'name' => env('APP_NAME', 'Laravel'),
    'app_debug' => env('APP_DEBUG', 'Laravel'),
    'concatenation' => env('CONCATENATION', '###'),
    //id passport
    "PASSWORD_CLIENT_ID" => env('PASSWORD_CLIENT_ID', 1),
    "PASSWORD_CLIENT_SECRET" => env('PASSWORD_CLIENT_SECRET', 'YOPvoSr7OCQI8iT3wvxWE2mtDHzVFfBuaDIe28q6'),
    "basic_auth" => [],
    // 'user_password_default' => 'root@123',
    // 'supper_password'       => 'root@123',
    'wiki' => [
        "username" => 'root',
        "password" => '123456'
    ],
    // header default 
    'header_default_api' => 'application/json; charset=UTF-8',
    'password_default' => 'Password@123'
];
