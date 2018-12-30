<?php

namespace System;

class Application
{
    /**
     * container
     *
     * @var array
     */
    private $container = [];

    /**
     * Application instance
     * @var object Application
     */
    private static $_instance;

    /**
     * Application constructor.
     *
     * @param File $file
     */
    private function __construct(File $file)
    {
        $this->share('file' , $file);
        $this->registerClasses();
        $this->loadHelpers();
    }

    /**
     * get Application instance
     *
     * @param File $file
     * @return object Application
     */
    public static function getInstance($file = null)
    {
        if (is_null(static::$_instance)){
            static::$_instance = new static($file);
        }
        return static::$_instance;
    }

    /**
     * run the Application
     *
     * @return void
     */
    public function run()
    {
        $this->session->start();
        $this->request->prepareUrl();
        $this->file->requireFile('App/routes/routes.php');
        list($controller , $method , $arguments) = $this->route->getProperRoute();

        $output = (string) $this->load->action($controller , $method , $arguments);
        $this->response->setOutput($output);
        $this->response->send($output);
    }

    /**
     * register class in spl_autoload_register
     * @return void
     */
    public function registerClasses()
    {
        spl_autoload_register([$this , 'load']);
    }

    /**
     * load classes
     *
     * @param $class
     */
    public function load($class)
    {
        if (strpos($class , 'App') === 0){
            $file = $class . '.php';
        }
        else{
            //get class from vendor
            $file = 'vendor/' . $class . '.php';
        }

        if ($this->file->exists($file)){
            $this->file->requireFile($file);
        }
    }

    /**
     * load helpers file
     * @return void
     */
    public function loadHelpers()
    {
        $file = 'vendor/helpers.php';
        $this->file->requireFile($file);
    }

    /**
     * share the given key|value Through Applicatio
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function share($key , $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->isSharing($name)){
            if ($this->isCoreAlias($name)){
                $this->share($name , $this->createCoreObject($name));
            }
            else{
                die('<b>' . $name . '</b> is not found in application container');
            }
        }
        return $this->container[$name];
    }

    /**
     * aliases classes
     * @return array
     */
    public function coreClasses()
    {
        return [
            'request'           => 'System\\Http\\Request',
            'response'          => 'System\\Http\\Response',
            'session'           => 'System\\Session',
            'route'             => 'System\\Route',
            'cookie'           => 'System\\Cookie',
            'load'              => 'System\\Loader',
            'html'              => 'System\\Html',
            'db'                => 'System\\Database',
            'view'              => 'System\\View\\ViewFactory',
            'url'               => 'System\\Url'
        ];
    }

    /**
     * determine if the given key is shared through application
     *
     * @param $key
     * @return bool
     */
    public function isSharing($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * @param $alias
     * @return bool
     */
    private function isCoreAlias($alias)
    {
        $coreClasses = $this->coreClasses();
        return isset($coreClasses[$alias]);
    }

    /**
     * create new object for the core class based on the given alias
     *
     * @param $alias
     * @return object
     */
    private function createCoreObject($alias)
    {
        $coreClasses = $this->coreClasses();
        $object = $coreClasses[$alias];

        return new $object($this);
    }
}