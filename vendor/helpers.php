<?php

if (!function_exists('pre'))
{
    function pre($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (!function_exists('array_get'))
{

    /**
     * get the $array[$key] else null
     *
     * @param array $array
     * @param string $key
     * @param null $default
     * @return null
     */
    function array_get($array , $key , $default = null){
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (!function_exists('_e')) {

    /**
     * escape the given value
     * @param string $value
     * @return string
     */
    function _e($value){
        return htmlspecialchars($value);
    }
}

if (!function_exists('assets')){

    /**
     * return the full path of the given path in public folder
     * @param string $path
     * @return string
     */
    function assets($path)
    {
        $app = \System\Application::getInstance();
        return $app->url->link('public/' . $path);
    }
}

/**
 * return the full path of the given path
 * @param string $path
 * @return string
 */
if (!function_exists('url'))
{
    function url($path)
    {
        $app = \System\Application::getInstance();
        return $app->url->link($path);
    }
}

if (!function_exists('extend'))
{
    function extend($path)
    {

    }
}