<?php

namespace System;


class Loader
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * controllers container
     * @var array $controllers
     */
    private $controllers = [];

    /**
     * models container
     * @var array $models
     */
    private $models = [];

    /**
     * Loader constructor.
     * @param object $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $controller
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function action($controller , $method , array $arguments)
    {
        $object = $this->controller($controller);
        return call_user_func_array([$object , $method] , $arguments);
    }

    /**
     * call the given controller
     * @param string $controller
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);
        if (!$this->hasController($controller)){
            $this->addController($controller);
        }
        return $this->getController($controller);
    }

    /**
     * check if the given controller exists
     * @param string $controller
     * @return bool
     */
    private function hasController($controller)
    {
        return array_key_exists($controller , $this->controllers);
    }

    /**
     * create new object of the given controller and store it in $controllers[]
     * @param string $controller
     * @return void
     */
    private function addController($controller)
    {
        $object = new $controller($this->app);
        $this->controllers[$controller] = $object;
    }

    /**
     * get the controller object
     * @param string $controller
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    /**
     * get the full name of controller
     * @param $controller
     * @return string
     */
    private function getControllerName($controller)
    {
        $controller .= 'Controller';
        $controller = 'App\\Controllers\\' . $controller;

        return str_replace('/' , '\\' , $controller);
    }

    /**
     * call the given model
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        $model = $this->getModelName($model);
        if (!$this->hasModel($model)){
            $this->addModel($model);
        }
        return $this->getModel($model);
    }

    /**
     * check if the given model exists
     * @param string $model
     * @return bool
     */
    private function hasModel($model)
    {
        return array_key_exists($model , $this->models);
    }

    /**
     * create new object of the given model and store it in $models[]
     * @param string $model
     * @return void
     */
    private function addModel($model)
    {
        $object = new $model($this->app);
        $this->models[$model] = $object;
    }

    /**
     * get the model object
     * @param string $model
     * @return object
     */
    private function getModel($model)
    {
        return $this->models[$model];
    }

    /**
     * get the full name of model
     * @param $model
     * @return string
     */
    private function getModelName($model)
    {
        $model .= 'Model';
        $model = 'App\\Models\\' . $model;

        return str_replace('/' , '\\' , $model);
    }

}