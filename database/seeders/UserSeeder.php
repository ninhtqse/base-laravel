<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Users\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $array = [
            'email'     => 'ninhtqse@gmail.com',
            'name'      => 'ninhtqse',
            'password'  => '123456',
            'created_at'=> now(),
            'updated_at'=> now()
        ];
        $user = User::create($array);
        // $user->assignRole(Role::ADMIN);
    }
}
