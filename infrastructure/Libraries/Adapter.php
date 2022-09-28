<?php

namespace Infrastructure\Libraries;

class Adapter
{

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    protected $web;
    protected $api;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Fluent API
    |--------------------------------------------------------------------------
    */
    public function withWeb(): self
    {
        $this->web = true;
        return $this;
    }

    public function withApi(): self
    {
        $this->api = true;
        return $this;
    }

    public function getWeb()
    {
        return $this->web;
    }

    public function getApi()
    {
        return $this->api;
    }
}