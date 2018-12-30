<?php
namespace System;


class Url
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * Url constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * generate link for given path
     * @param string $path
     * @return string
     */
    public function link($path)
    {
        return $this->app->request->baseUrl() . '/' . trim($path , '/');
    }

    /**
     * redirect to given path
     * @param string $path
     */
    public function redirectTo($path)
    {
        header('Location: ' . $this->link($path));
        exit;
    }


}