<?php
namespace System\View;


interface ViewInterface
{

    /**
     * get the view output
     * @return string
     */
    public function getOutput();

    /**
     * convert object to string
     * @return string
     */
    public function __toString();
}