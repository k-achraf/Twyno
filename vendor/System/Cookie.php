<?php
namespace System;


class Cookie
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * Cookie constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * add new value to cookie
     *
     * @param int $name
     * @param mixed $value
     * @param int $time
     */
    public function set($name , $value , $time = 1800)
    {
        setcookie($name , $value , time() + $time * 3600 , '' , '' , false , true);
    }

    /**
     * get value from cookie
     *
     * @param string $name
     * @return mixed
     */
    public function get($name , $default = null)
    {
        return array_get($_COOKIE , $name , $default);

    }

    /**
     * check exists of cookie
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * remove cookie value
     * @param string $name
     */
    public function remove($name)
    {
        setcookie($name , null , -1);
        unset($_COOKIE[$name]);
    }

    /**
     * get all data
     * @return mixed
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * remove all cookie data
     */
    public function destroy()
    {
        foreach (array_keys($this->all()) as $key)
        {
            $this->remove($key);
        }
        unset($_COOKIE);
    }
}