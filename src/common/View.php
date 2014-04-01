<?php

namespace Application\Common;

class View
{

    protected $serviceFactory;

    protected $templateBuilder;

    public function __construct($serviceFactory, $templateBuilder)
    {
        $this->serviceFactory = $serviceFactory;
        $this->templateBuilder = $templateBuilder;
    }
}