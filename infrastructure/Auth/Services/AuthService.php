<?php

namespace Infrastructure\Auth\Services;

use Infrastructure\Exceptions as IncException;
use App\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Crypt;
use Infrastructure\Auth\LoginProxy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Libraries\Mail;

class AuthService
{

    private $mail;

    private $loginProxy;

    private $userRepository;

    public function __construct(
        Mail $mail,
        LoginProxy $loginProxy,
        UserRepository $userRepository
    ) {
        $this->mail           = $mail;
        $this->loginProxy     = $loginProxy;
        $this->userRepository = $userRepository;
    }

    public function login($email, $password)
    {
        $user =  $this->userRepository->getWhere('email', $email)->first();
        if (!$user) {
            throw new IncException\GeneralException('AWE005');
        }
        try {
            // đăng nhập
            $data   = $this->loginProxy->attemptLogin($user->id, $password);
        } catch (\Exception $e) {
            throw $e;
        }
        $data['user'] = $user;
        return $data;
    }

    public function refresh($refresh_token)
    {
        return $this->loginProxy->attemptRefresh($refresh_token);
    }

    public function logout()
    {
        $this->loginProxy->logout();
    }

    public function forgotPassword($email)
    {
        $user = $this->userRepository->getWhere('email', $email)->first();
        if (!$user) {
            throw new IncException\GeneralException('AWE008');
        }
        $date    = date('Y-m-d H:i:s');
        $expires_forgot_token = date('Y-m-d H:i:s', strtotime('+2 day', strtotime($date)));
        $token = $email . concatenation() . $expires_forgot_token;
        $data = [
            'token' => Crypt::encryptString($token),
            'email' => $email
        ];
        $this->userRepository->update($user, ['forgot_token' => $data['token']]);
        $data['link'] = client() . \Config('config.client.page.forgot_password') . $data['token'];
        $data['name'] = $user->name;
        $this->mail->sendMailForgotPassword('mails.auth.'.lang().'.forgot_password', $data);
    }

    public function resetPassword($token, $password)
    {
        dd(2);
        $encode= $token;
        $token = Crypt::decryptString($token);
        $data  = explode(concatenation(), $token);
        $email = $data[0];
        $user  = $this->userRepository->getWhereArray([
            'email' => $email, 'forgot_token' => $encode
        ])->first();

        if (!$user || !$data || $user->forgot_token != $encode) {
            throw new IncException\GeneralException('AWE010');
        }
        $expires_forgot_token = $data[1];
        if ($expires_forgot_token < date('Y-m-d H:i:s')) {
            throw new IncException\GeneralException('AWE009');
        }
        $this->userRepository->update($user, [
            'password' => $password,
            'forgot_token' => null
        ]);
    }

    public function changePassword($password_old, $password_new)
    {
        $user  = getUser();
        $check = Hash::check($password_old, $user->password);
        if (!$check) {
            throw new IncException\GeneralException('AWE011');
        }
        $this->userRepository->update($user, ['password' => $password_new]);
    }
}
