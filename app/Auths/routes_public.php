<?php

$router->get('/login','AuthController@getLogin');
$router->post('/login','AuthController@postLogin');