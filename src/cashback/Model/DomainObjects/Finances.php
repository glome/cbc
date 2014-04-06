<?php

namespace Application\DomainObjects;

class Finances
{

    private $userId;
    private $error = null;

    public function setUserId($id)
    {
        $this->userId = $id;
    }


    public function getUserId()
    {
        return $this->userId;
    }


    public function setError($message)
    {
        $this->error = $message;
    }

    public function hasError()
    {
        return $this->error !== null;
    }

    public function getErrorMessage()
    {
        return $this->error;
    }


    public function getBacklog()
    {
        return [];
    }


}