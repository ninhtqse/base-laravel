<?php

namespace App\Auths\Controllers;

use App\Auths\Requests\LoginRequest;
use App\Auths\Services\AuthService;
use Infrastructure\Http\Controller;

class AuthController extends Controller{

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function getLogin()
    {
        return view('auths.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $email      = $request->get('email');
        $password   = $request->get('password');
        return $this->authService->login($email, $password);
    }
}