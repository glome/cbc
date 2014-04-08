<?php

namespace Application\DomainObjects;

class Visit
{

    private $visitorId;
    private $categoryId;
    private $productId;
    private $link;
    private $userId;


    public function setUserId($id)
    {
        $this->userId = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }


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


    public function setTrackingLink($link)
    {
        $this->link = $link;
    }

    public function getTrackingLink()
    {
        return $this->link;
    }

    public function setError($code, $message)
    {
        if ($code != 23000) {
            // swollow
        }
    }


}