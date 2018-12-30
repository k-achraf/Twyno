<?php

namespace System;


abstract class T_Model
{

    /**
     * Application object
     * @var object $app
     */
    protected $app;

    /**
     * T_Controller constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * call shared application object dynamically
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->$key;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->app->db , $method], $arguments);
    }
}