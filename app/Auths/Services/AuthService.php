<?php

namespace App\Auths\Services;

use Illuminate\Support\Facades\Auth;

class AuthService{

    /**
     * 
     */
    public function login(string $email, string $password)
    {
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect(constants('uri.home'));
        }else{
            return back()->withInput(['email' => $email])->withErrors(['errors' => __('info_account_incorrect')]);
        }
    }
}