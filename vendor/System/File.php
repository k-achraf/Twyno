<?php
/**
 * Created by PhpStorm.
 * User: ashi04
 * Date: 12/17/2018
 * Time: 12:51 AM
 */

namespace System;


class File
{
    /**
     * Directory Separator caonstant
     * @const String
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * root path
     *
     * @var String
     */
    private $root;

    /**
     * File constructor.
     *
     * @param String $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * determine wether the given filepath exists
     *
     * @param $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($this->to($file));
    }

    /**
     * require the given file
     *
     * @param $file
     * @return mixed
     */
    public function requireFile($file)
    {
        return require $this->to($file);
    }

    /**
     * generate full path to the given path in vendor folder
     *
     * @param $path
     * @return mixed
     */
    public function toVendor($path)
    {
        return $this->to('vendor/' . $path);
    }

    /**
     * generate full path to the given path
     *
     * @param $path
     * @return string
     */
    public function to($path)
    {
        return $this->root . static::DS . str_replace(['/' , '\\'] , static::DS , $path);
    }
}