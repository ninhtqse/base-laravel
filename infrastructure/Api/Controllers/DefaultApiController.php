<?php

namespace Infrastructure\Api\Controllers;

use Infrastructure\Http\Controller as BaseController;

class DefaultApiController extends BaseController
{
    protected $cookie;

    protected $helperFunction;

    public function __construct(){}

    public function index()
    {
        return response()->json([
            'title'   => 'api-amela-crm',
            'version' => '1.0'
        ]);
    }

    public function wiki()
    {
        return $this->verify('WIKI');
    }

    //=================> SUPPORT METHOD <=========================
    private function showLogin()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Text to send if user hits Cancel button';
        exit;
    }

    private function verify($type)
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->showLogin();
        } else {
            if ($_SERVER['PHP_AUTH_USER'] == \Config('config.wiki.username') &&
                $_SERVER['PHP_AUTH_PW'] == \Config('config.wiki.password')
            ) {
                if ($type == 'WIKI') {
                    $html = file_get_contents(public_path('/wiki/main/dist/3.0/output.html'));
                    return $html;
                } elseif ($type == 'DATABASE') {
                    return view('docs.database')->with('database', $this->arrayDatabase());
                }
            } else {
                $this->showLogin();
            }
        }
    }
}
