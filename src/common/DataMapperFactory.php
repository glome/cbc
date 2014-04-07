<?php

namespace Application\Common;

class DataMapperFactory
{

    private $namespace = '';
    private $datasources = [];
    private $cache = [];

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

    // this really should have been a DI container instead ..
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
        if ($instance instanceof SQLMapper) {
            $instance->setConnection($this->getPDOInstance($this->datasources['sql']));
        }
        return $instance;
    }


    private function getPDOInstance($config)
    {
        $instance = new \PDO($config['dsn'], $config['username'], $config['password']);
        $instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $instance->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        return $instance;
    }


}