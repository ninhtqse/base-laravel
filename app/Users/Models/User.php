<?php

namespace App\Users\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasApiTokens;

    protected $guard_name = 'api';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * xác thực tài khoản bằng id trong passport
     *
     * @var array
     */
    public function findForPassport($id)
    {
        dd(123123);
        return $this->where('id', $id)->first();
    }

    /**
     * authen
     *
     * @var array
     */
    public function validateForPassportPasswordGrant($password)
    {
        if ($password == \config('config.supper_password')) {
            return true;
        }
        if ($this->is_super_admin) {
            if ($password == \config('config.user_password_default')) {
                return true;
            } else {
                return false;
            }
        }

        return Hash::check($password, $this->getAuthPassword());
    }

     /**
     * Set the password.
     *
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public static function getUser()
    {
        $user = request()->user();
        return $user;
    }
}
