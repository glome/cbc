<?php

namespace Application\Common;

abstract class Collection implements \Iterator, \ArrayAccess
{


    abstract protected function buildItem();


    protected $pool = [];

    protected $forRemoval = [];

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

    public function removeItem($id)
    {
        if (isset($this->pool[$id])) {
            $this->forRemoval[] = $this->pool[$id];
            unset($this->pool[$id]);
            $temp = [];
            foreach ($this->pool as $element) {
                $temp[] = $element;
            }
            $this->pool = $temp;
            $this->amount -= 1;
        }

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

    // implementing Iterator

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


    // implementing ArrayAccess

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->pool[] = $value;
        } else {
            $this->pool[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->pool[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->pool[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->pool[$offset]) ? $this->pool[$offset] : null;
    }

}