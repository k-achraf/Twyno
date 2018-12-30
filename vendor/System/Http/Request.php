<?php
namespace System\Http;


class Request
{

    /**
     * url
     * @var string $url
     */
    private $url;

    /**
     * base url
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * prepare url
     * @return void
     */
    public function prepareUrl()
    {
        $script = dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');

        if (strpos($requestUri , '?') !== false){
            list($requestUri , $queryString) = explode('?' , $requestUri);
        }

        $this->url = preg_replace('#^'.$script.'#' , '' , $requestUri);
        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script;

    }

    /**
     * get $_SERVER[$key]
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function server($key , $default = null)
    {
        return array_get($_SERVER , $key , $default);
    }

    /**
     * get the clean url
     *
     * @return string
     */
    public function url(){
        return $this->url;
    }

    /**
     * get request method
     *
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (count($_GET) > 0){
            return array_get($_GET , $name , null);
        }
        if (count($_POST) > 0){
            return array_get($_POST , $name , null);
        }
        return null;
    }
}