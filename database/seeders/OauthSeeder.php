<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //truncate
        DB::table('oauth_clients')->truncate();
        DB::table('oauth_personal_access_clients')->truncate();
        //get data seeder
        $path = database_path('seeders/json/oauths/');
        $oauth_clients   = decode_file_json($path.'oauth_clients.json');
        $oauth_personal  = decode_file_json($path.'oauth_personal.json');
        //insert
        DB::table('oauth_clients')->insert($oauth_clients);
        DB::table('oauth_personal_access_clients')->insert($oauth_personal);
    }
}
