<?php
namespace System;


class Html
{
    /**
     * Application object
     * @var Application $app
     */
    private $app;

    /**
     * html title
     * @var string $title
     */
    private $title;

    /**
     * html descriprion
     * @var string $description
     */
    private $description;

    /**
     * html keywords
     * @var string $keywords
     */
    private $keywords;

    /**
     * Html constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }


}