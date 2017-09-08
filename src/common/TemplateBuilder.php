<?php

namespace Application\Common;

class TemplateBuilder
{
    private $path;

    private $translations;

    public function __construct($path, $translations)
    {
        $this->path = $path;
        $this->translations = $translations;
    }

    public function create($name)
    {
        $filepath = $this->path . '/' . $name . '.html';
        $instance = new Template($filepath);
        $instance->useTranslations($this->translations->fetchTranslations());
        return $instance;
    }
}
