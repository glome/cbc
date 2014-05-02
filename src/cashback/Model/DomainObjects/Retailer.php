<?php

namespace Application\DomainObjects;

class Retailer
{
    private $id = null;
    private $name = null;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParsedArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
