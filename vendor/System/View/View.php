<?php
namespace System\View;


use System\File;

class View implements ViewInterface
{

    /**
     * File object
     * @var File $file
     */
    private $file;

    /**
     * view path
     * @var string $viewPath
     */
    private $viewPath;

    /**
     * passed data to the view
     * @var array $data
     */
    private $data = [];

    /**
     * the output from the view file
     * @var string $output
     */
    private $output;

    /**
     * View constructor.
     * @param File $file
     * @param string $viewPath
     * @param array $data
     */
    public function __construct(File $file, $viewPath, array $data)
    {
        $this->file = $file;
        $this->preparePath($viewPath);
        $this->data = $data;
    }

    /**
     * prepare view path
     * @param string $viewPath
     * @return void
     */
    private function preparePath($viewPath)
    {
        $relativeViewPath = 'App/Views/' . $viewPath . '.php';
        $this->viewPath = $this->file->to($relativeViewPath);
        if (!$this->viewFileExist($relativeViewPath)){
            die('<b>' . $viewPath . ' view </b> does not found!');
        }
    }

    /**
     * determine if the view exists
     * @param string $viewPath
     * @return bool
     */
    private function viewFileExist($viewPath)
    {
        return $this->file->exists($viewPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        if (is_null($this->output)){
            ob_start();
            extract($this->data);
            require $this->viewPath;
            $this->output = ob_get_clean();
        }

        return $this->output;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getOutput();
    }

}