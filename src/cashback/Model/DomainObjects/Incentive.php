<?php

namespace Application\DomainObjects;

class Incentive
{
    private $id;
    private $fixedPercentage;
    private $variablePercentage;
    private $code;
    private $timeString;
    private $programId;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFixedPercentage($fixedPercentage)
    {
        $this->fixedPercentage = $fixedPercentage;
    }

    public function setVariablePercentage($variablePercentage)
    {
        $this->variablePercentage = $variablePercentage;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setExpiresAt($time)
    {
        $this->timeString = $time;
    }

    public function setProgramId($id)
    {
        $this->programId = $id;
    }

    public function getProgramId()
    {
        return $this->programId;
    }

    public function getVoucher()
    {
        return $this->code;
    }

    public function getParsedArray()
    {
        if ($this->variablePercentage === '0.0') {
            return false;
        }
        return [
            'code' => $this->code,
            'percentage' => $this->variablePercentage,
        ];
    }
}
