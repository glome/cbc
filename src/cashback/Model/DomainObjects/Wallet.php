<?php

namespace Application\DomainObjects;

class Wallet
{

    private $userId;
    private $response = [];

    public function setUserId($id)
    {
        $this->userId = $id;
    }


    public function getUserId()
    {
        return $this->userId;
    }


    public function setResponse($response)
    {
        $this->response = $response;
    }


    public function getParsedArray()
    {
        return $this->response;
    }
}
