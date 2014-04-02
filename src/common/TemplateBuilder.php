<?php

namespace Application\Common;

class TemplateBuilder
{

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }


    public function create($name)
    {
        $filepath = $this->path . '/' . $name . '.html';
        $instance = new Template($filepath);
        return $instance;
    }

}