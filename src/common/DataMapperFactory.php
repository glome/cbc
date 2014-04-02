<?php

namespace Application\Common;

class DataMapperFactory
{

    private $namespace = '';
    private $provider = null;
    private $connection = null;


    public function __construct(callable $provider = null)
    {
        $this->provider = $provider;
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
        if ( $this->connection === null  && $this->provider !== null)
        {
            $this->connection = call_user_func( $this->provider );
        }

        if ($namespace !== null) {
            $class = $namespace . '\\' . $class;
        }

        $class = $this->namespace . '\\' . $class;
        $instance = new $class( $this->connection );
        if ($instance instanceof CookieMapper) {
            $instance->setCookieJar($this->shared['CookieJar']);
        }
        return $instance;
    }


}