<?php

namespace Application\DomainObjects;

class ProductCollection extends \Application\Common\Collection
{

    private $categoryId = null;

    private $query = null;


    protected function buildItem()
    {
        return new Product;
    }

    public function setCategory($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function hasCategory()
    {
        return $this->categoryId !== null;
    }

    public function getCategory()
    {
        return $this->categoryId;
    }

    public function setQuery($query)
    {
        $query = trim($query);
        if (!empty($query)) {
            $this->query = $query;
        }
    }

    public function hasQuery()
    {
        return $this->query !== null;
    }


    public function getQuery()
    {
        return $this->query;
    }



    public function getParsedArray()
    {
        $data = [];
        foreach ($this as $product) {

            $id = $product->getId();
            $data[$id] = $product->getParsedArray();
        }

        return $data;
    }



}