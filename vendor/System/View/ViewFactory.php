<?php

namespace System\View;


use System\Application;

class ViewFactory
{
    /**
     * Application object
     * @var object $app
     */
    private $app;

    /**
     * ViewFactore constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $viewPath
     * @param array $data
     * @return ViewInterface
     */
    public function render($viewPath , array $data = [])
    {
        return new View($this->app->file , $viewPath , $data);
    }

}