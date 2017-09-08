<?php

namespace Application\Common;

class ServiceFactory
{
    private $namespace;

    private $cache = [];

    private $domainObjectFactory;

    private $dataMapperFactory;

    public function __construct($domainObjectFactory, $dataMapperFactory)
    {
        $this->domainObjectFactory = $domainObjectFactory;
        $this->dataMapperFactory = $dataMapperFactory;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function create($name)
    {
        $class = $this->namespace . '\\' . $name;

        if (array_key_exists($class, $this->cache) === false) {
            $instance = new $class( $this->domainObjectFactory, $this->dataMapperFactory );
            $this->cache[ $class ] = $instance;
        }

        return $this->cache[ $class ];
    }
}
