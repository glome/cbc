<?php

namespace Application\DomainObjects;

class Visit
{

    private $visitorId;
    private $categoryId;
    private $productId;


    public function setVisitorId($visitorId)
    {
        $this->visitorId = $visitorId;
    }

    public function getVisitorId()
    {
        return $this->visitorId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getProductId()
    {
        return $this->productId;
    }



    public function setError($code, $message)
    {
        if ($code != 23000) {
            // swollow
        }
    }


}