<?php

namespace Application\Common;

abstract class Collection
{
    abstract protected function buildItem();


    protected $pool = [];

    public function addItem($parameters)
    {
        $instance = new \Application\DomainObjects\Category;
        foreach ($parameters as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }


        $this->pool[] = $instance;
    }

}