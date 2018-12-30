<?php
namespace System;


class T_Controller
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

}