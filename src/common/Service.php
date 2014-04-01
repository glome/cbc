<?php

namespace Application\Common;

class Service
{
    protected $domainObjectFactory;

    protected $errors = [];

    public function __construct( $domainObjectFactory, $dataMapperFactory )
    {
        $this->domainObjectFactory = $domainObjectFactory;
        $this->dataMapperFactory = $dataMapperFactory;
    }


    protected function addError( $message )
    {
        $this->errors[] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

