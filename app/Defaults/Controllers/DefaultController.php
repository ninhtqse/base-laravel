<?php

namespace App\Defaults\Controllers;

use Illuminate\Support\Facades\Auth;

class DefaultController{

    public function index()
    {
        $default = 'Welcome';
        return view('welcome', compact('default'));
    }
}