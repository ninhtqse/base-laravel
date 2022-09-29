<?php

namespace App\Supports\Controllers;

use Infrastructure\Http\Controller;

class SupportController extends Controller
{
    public function notFound()
    {
        return view('supports.errors.not_found');
    }
}