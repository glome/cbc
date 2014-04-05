<?php

namespace Application\Common;

class DataMapperFactory
{

    private $namespace = '';
    private $datasources = [];


    public function __construct($datasources = null)
    {
        $this->datasources = $datasources;
    }

    public function setNamespace( $namespace )
    {
        $this->namespace = $namespace;
    }

    public function setShared($shared)
    {
        $this->shared = $shared;
    }


    public function create( $class, $namespace = null )
    {


        if ($namespace !== null) {
            $class = $namespace . '\\' . $class;
        }

        $class = $this->namespace . '\\' . $class;
        $instance = new $class($this->datasources);
        if ($instance instanceof CookieMapper) {
            $instance->setCookieJar($this->shared['CookieJar']);
        }
        if ($instance instanceof CacheMapper) {
            $instance->setCache($this->shared['Cache']);
        }
        return $instance;
    }


}