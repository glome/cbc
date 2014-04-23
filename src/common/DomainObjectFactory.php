<?php

namespace Application\Common;

class DomainObjectFactory
{

    private $namespace = '';


    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }


    public function create($class, $namespace = null)
    {
        if ($namespace !== null) {
            $class = $namespace . '\\' . $class;
        }

        $class = $this->namespace . '\\' . $class;
        $instance = new $class;
        return $instance;
    }
}
