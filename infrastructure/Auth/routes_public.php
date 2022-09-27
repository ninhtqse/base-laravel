<?php

$router->post('/login', 'AuthController@login');
$router->post('/login/refresh', 'AuthController@refresh');
$router->post('/get-token', 'AuthController@getToken');
$router->post('/login-once', 'AuthController@loginOnce');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->post('/reset-password', 'AuthController@resetPassword');
