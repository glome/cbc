<?php

namespace Application\Common;

class Controller
{

    protected $serviceFactory;

    public function __construct($serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }
}
