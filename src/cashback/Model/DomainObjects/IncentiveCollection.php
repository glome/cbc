<?php

namespace Application\DomainObjects;

class IncentiveCollection extends \Application\Common\Collection
{
    private $errorCode = null;
    private $errorMessage = null;

    protected function buildItem()
    {
        return new Incentive;
    }

    public function locateIncentive($id)
    {
        foreach ($this as $incentive) {
            $current = $incentive->getId();
            if ($current == $id) {
                return $incentive;
            }
        }

        return false;
    }

    public function getError()
    {
        return ['code' => $this->errorCode, 'message' => $this->errorMessage];
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setErrorCode($code)
    {
        $this->errorCode = $code;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

}
