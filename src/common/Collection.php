<?php

namespace Application\Common;

abstract class Collection implements \Iterator
{


    abstract protected function buildItem();


    protected $pool = [];

    private $position = 0;

    private $amount = 0;

    public function addItem($parameters)
    {
        $instance = $this->buildItem();
        foreach ($parameters as $key => $value) {
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }

        $this->amount += 1;
        $this->pool[] = $instance;
        return $instance;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function __construct() {
        $this->position = 0;
    }

    public function hasItems()
    {
        return $this->amount > 0;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->pool[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->pool[$this->position]);
    }


}