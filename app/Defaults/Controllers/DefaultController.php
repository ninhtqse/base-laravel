<?php

namespace App\Defaults\Controllers;

class DefaultController{

    public function index()
    {
        $default = 'Welcome';
        return view('welcome', compact('default'));
    }
}