<?php

namespace App\Homes\Controllers;

use Infrastructure\Http\Controller;

class HomeController extends Controller{

    public function getHome()
    {
        return view('homes.index');
    }
}