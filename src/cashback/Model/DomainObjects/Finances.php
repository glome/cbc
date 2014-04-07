<?php

namespace Application\DomainObjects;

class Finances
{

    private $userId;
    private $error = null;
    private $earnings = [];

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


    public function getTotal($currency = null)
    {
        if (isset($this->earnings['fresh']['total'][$currency])) {
            return $this->earnings['fresh']['total'][$currency];
        }
        return 0;
    }


    public function setEarnings($earnings)
    {
        $this->earnings = $earnings;
    }


}