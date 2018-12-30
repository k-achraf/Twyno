<?php
namespace System;


class Route
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * routes container
     * @var array
     */
    private $routes = [];

    /**
     * not found url
     *
     * @var string $notFound
     */
    private $notFound;

    /**
     * Route constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $url
     * @param string $action
     * @param string $method
     * @return void
     */
    public function add($url , $action , $method = 'GET')
    {
        $route = [
            'url'       => $url,
            'pattern'   => $this->generatePattern($url),
            'action'    => $this->getAction($action),
            'method'    => $method
        ];
        $this->routes[] = $route;
    }

    /**
     * generate regex pattern for the given url
     * @param $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';

        //:text([a-zA-z0-9-])
        //:id(\d+)
        $pattern .= str_replace([':text' , ':id'] , ['([a-zA-z0-9-]+)' , '(\d+)'] , $url);
        $pattern .= '$#';

        return $pattern;
    }

    /**
     * get the proper action
     *
     * @param string $url
     * @return string
     */
    private function getAction($action)
    {
        $action = str_replace('/' , '\\' , $action);
        return strpos($action , '@') !== false ? $action : $action . '@index';
    }

    /**
     * set not found url
     * @param string $url
     */
    public function notFound($url)
    {
        $this->notFound = $url;
    }

    /**
     * get the propper route
     *
     * @return array
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route){
            if ($this->isMatching($route['pattern'])){
                $arguments = $this->getArgumentsFrom($route['pattern']);
                //controller@method
                list($controller , $method) = explode('@' , $route['action']);
                return [$controller , $method , $arguments];
            }
        }
    }

    /**
     * determinate if the given pattern matches the curent request url
     *
     * @param string $pattern
     * @return false|int
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern , $this->app->request->url());
    }

    /**
     * get the argument from the request url based on the given pattern
     *
     * @param string $pattern
     * @return array
     */
    private function getArgumentsFrom($pattern)
    {
        preg_match($pattern , $this->app->request->url() , $matches);
        array_shift($matches);

        return $matches;
    }
}