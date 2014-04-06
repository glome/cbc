<?php

namespace Application\DomainObjects;

class Wish
{

    private $visitorId;
    private $productId;


    public function setVisitorId($id)
    {
        $this->visitorId = $id;
    }


    public function getVisitorId()
    {
        return $this->visitorId;
    }

    public function setProductId($id)
    {
        $this->productId = $id;
    }


    public function getProductId()
    {
        return $this->productId;
    }


    public function setError($code, $message)
    {
        if ($code != 23000) {
            // add handling later
        }
    }

}