<?php

namespace Application\Common;

abstract class Collection implements \Iterator, \ArrayAccess
{
    abstract protected function buildItem();

    protected $pool = [];
    protected $forRemoval = [
                  'pool' => [],
                  'keys' => [],
              ];
    private $position = 0;
    private $amount = 0;

    public function addItem($parameters)
    {
        $instance = $this->buildItem();

        foreach ((array)$parameters as $key => $value) {
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
            $this->forRemoval['pool'][] = $this->pool[$id];
            $this->forRemoval['keys'][] = $id;
        }
    }

    public function cleanup()
    {
        foreach ($this->forRemoval['keys'] as $id) {
            unset($this->pool[$id]);
        }

        $temp = [];
        foreach ($this->pool as $element) {
            $temp[] = $element;
        }
        $this->pool = $temp;

        $this->amount = count($this->pool);
        $this->forRemoval = [
            'pool' => [],
            'keys' => [],
        ];
    }

    public function getRemovable()
    {
        return $this->forRemoval;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function __construct()
    {
        $this->position = 0;
    }

    public function hasItems()
    {
        return $this->amount > 0;
    }

    // implementing Iterator
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->pool[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->pool[$this->position]);
    }

    // implementing ArrayAccess
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->pool[] = $value;
        } else {
            $this->pool[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->pool[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->pool[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->pool[$offset]) ? $this->pool[$offset] : null;
    }
}
