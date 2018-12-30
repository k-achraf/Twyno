<?php
namespace System\Http;


use System\Application;

class Response
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * headers container that will be send to the browser
     * @var array $headers
     */
    private $headers = [];

    /**
     * content thet will be sent to the browser
     * @var string $content
     */
    private $content = '';

    /**
     * Response constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * set the response output
     * @param string $content
     * @return void
     */
    public function setOutput($content)
    {
        $this->content = $content;
    }

    /**
     * set the response headers
     * @param string $header
     * @param mixed $value
     * @return void
     */
    public function setHeaders($header , $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * send the response headers and content
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendOutput();
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value){
            header($header . ':' . $value);
        }
    }

    private function sendOutput()
    {
        echo $this->content;
    }
}