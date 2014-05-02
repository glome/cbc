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

    public function getBacklog($currency)
    {
        if (isset($this->earnings['fresh'][$currency])) {
            foreach ($this->earnings['fresh'][$currency]['details'] as $key => $value) {
                $datetime = new \DateTime($this->earnings['fresh'][$currency]['details'][$key]['date']);
                $amount = number_format($value['amount']/100, 2, '.', '');
                $this->earnings['fresh'][$currency]['details'][$key]['amount'] = $amount;
                $this->earnings['fresh'][$currency]['details'][$key]['date'] = $datetime->format('d/m/Y');
            }
            return $this->earnings['fresh'][$currency];
        }

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
