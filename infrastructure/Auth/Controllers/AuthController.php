<?php

namespace Infrastructure\Auth\Controllers;

use Infrastructure\Auth\Requests\ResetPasswordRequest;
use Infrastructure\Auth\Requests\ForgotPasswordRequest;
use Infrastructure\Auth\Requests\ChangePasswordRequest;
use Infrastructure\Libraries\Response as Response;
use Infrastructure\Auth\Requests\RefreshRequest;
use Infrastructure\Auth\Requests\LoginRequest;
use Infrastructure\Auth\Services\AuthService;
use Infrastructure\Http\Controller;
use Infrastructure\Libraries\Mail;

class AuthController extends Controller
{
    private $authService;

    private $response;

    public function __construct(
        Mail $mail,
        Response $response,
        AuthService $authService
    ) {
        $this->authService       = $authService;
        $this->response          = $response;
        $this->mail              = $mail;
    }

    public function login(LoginRequest $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');
        $result   = $this->authService->login($email, $password);
        return $this->response->renderSuccess('AWS001', $result);
    }

    public function refresh(RefreshRequest $request)
    {
        $data = $this->authService->refresh($request->refresh_token);
        return $this->response->renderSuccess('AWS001', $data);
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->response->renderSuccess('AWS001');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $this->authService->forgotPassword($email);
        return $this->response->renderSuccess('AWS001');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $this->authService->resetPassword($request->token, $request->password);
        return $this->response->renderSuccess('AWS001', $data);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $password_old = $request->password_old;
        $password_new = $request->password_new;
        $this->authService->changePassword($password_old, $password_new);
        return $this->response->renderSuccess('AWS001');
    }
}
