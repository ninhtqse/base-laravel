<?php

namespace Infrastructure\Testing\Feature\Auth;

use Infrastructure\Testing\BaseTest\BaseFeatureTrait;
use Infrastructure\Testing\TestCase;

class AuthTest extends TestCase
{
    use BaseFeatureTrait;

    const CASE1 = 1;
    const CASE2 = 2;
    const CASE3 = 3;

    /**
     * @param array $arr
     * @dataProvider data_provider_login
     */
    public function test_login($arr)
    {
        $response = $this->json('POST','/api/v1/login', $arr, []);
        switch ($arr['case']) {
            case self::CASE1:
                $response->assertStatus(200)->assertJsonStructure([
                    "data" => [
                        'access_token',
                        'expires_in',
                        'refresh_token',
                        'user'
                    ],
                    "status",
                    "code"
                ]);
                // $this->assertAuthenticated();
                break;
            case self::CASE2:
                $response->assertStatus(401)->assertJson([
                    'status' => "error",
                    'code' => "AWE005",
                ]);
                break;
            case self::CASE3:
                $response->assertStatus(401)->assertJson([
                    'status' => "error",
                    'code' => "AWE005",
                ]);
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function data_provider_login()
    {
        //success
        $case1 = [
            'email' => 'ninhtqse@gmail.com',
            'password' => '123456',
            'case' => self::CASE1
        ];
        //not valid email
        $case2 = [
            'email' => 'ninhtqse1@gmail.com',
            'password' => '123456',
            'case' => self::CASE2
        ];
        //not valid password
        $case3 = [
            'email' => 'ninhtqse@gmail.com',
            'password' => '1234567',
            'case' => self::CASE3
        ];
        return [
            [$case1],
            [$case2],
            [$case3]
        ];
    }
}
