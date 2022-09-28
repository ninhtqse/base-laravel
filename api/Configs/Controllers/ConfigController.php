<?php

namespace Api\Configs\Controllers;

use Infrastructure\Http\Controller;
use Infrastructure\Libraries\Response;

class ConfigController extends Controller{

    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        return $this->response->renderSuccess('AWS001');
    }
}