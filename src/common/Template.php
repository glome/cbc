<?php

namespace Application\Common;

class Template
{

    private $parameters = [];

    private $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }


    public function assign($name, $value)
    {
        $this->parameters[$name] = $value;
    }


    public function append($name, $value)
    {
        $type = gettype($value);
        if (in_array($type, ['array', 'integer', 'string'])) {
            $this->{'append' . $type}($name, $value);
        }

    }

    private function appendArray($name, $value)
    {
        if ( !array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = [];
        }

        $this->parameters[$name] += $value;
    }


    private function appendInteger($name, $value)
    {
        if ( !array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = 0;
        }

        $this->parameters[$name] += $value;
    }


    private function appendString($name, $value)
    {
        if ( !array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = '';
        }

        $this->parameters[$name] .= $value;
    }

    public function render()
    {
        ob_start();
        foreach ($this->parameters as $key => $value) {
            if ($value instanceof \Common\Template) {
                $this->parameters[$key] = $value->render();
            }
        }
        extract($this->parameters, \EXTR_SKIP);
        require $this->filepath;
        return ob_get_clean();
    }

}