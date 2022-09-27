<?php

namespace App\Users\Repositories;

use Infrastructure\Database\Eloquent\Repository;
use App\Users\Models\User;

class UserRepository extends Repository
{
    public function getModel()
    {
        return new User();
    }
}
