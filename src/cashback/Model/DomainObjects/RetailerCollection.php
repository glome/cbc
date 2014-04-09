<?php

namespace Application\DomainObjects;

class RetailerCollection extends \Application\Common\Collection
{

    private $categoryId = null;


    protected function buildItem()
    {
        return new Retailer;
    }


    public function setCategoryId($id)
    {
        $this->categoryId = $id;
    }


    public function getCategoryId()
    {
        return $this->categoryId;
    }


    public function getParsedArray()
    {
        $list = [];
        foreach ($this as $item) {
            $list[$item->getId()] = $item->getParsedArray();
        }
        return $list;
    }


}