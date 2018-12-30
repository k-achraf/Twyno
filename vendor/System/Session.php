<?php
/**
 * Created by PhpStorm.
 * User: ashi04
 * Date: 12/18/2018
 * Time: 12:01 AM
 */

namespace System;


class Session
{
    /**
     * Application object
     *
     * @var Application $app
     */
    private $app;

    /**
     * Session constructor.
     * @param object $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * start new session
     *
     * @return void
     */
    public function start()
    {
        ini_set('session.use_only_cookies' , 1);
        if (!session_id()){
            session_start();
        }
    }

    /**
     * set new value of session
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key , $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * get the value of session
     *
     * @param string $key
     * @return mixed
     */
    public function get($key , $default = null)
    {
        return array_get($_SESSION , $key , $default);
    }

    /**
     * check if session exists
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * remove $_Session[$key]
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * get $_Session[$key] and remove it
     *
     * @param string $key
     * @return mixed
     */
    public function pull($key)
    {
        $value = $this->get($key);
        $this->remove($key);
        return $value;
    }

    /**
     * get all session data
     *
     * @return mixed
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * destroy session
     *
     * @return void
     */
    public function destroy()
    {
        session_destroy();
        unset($_SESSION);
    }

}