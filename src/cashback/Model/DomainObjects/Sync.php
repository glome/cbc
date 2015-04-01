<?php

namespace Application\DomainObjects;

class Sync
{
    private $id;
    private $userId;
    private $pairedId;
    private $code_1;
    private $code_2;
    private $code_3;
    private $errorCode;
    private $errorMessage;
    private $pairingCode;
    private $brothers;
    private $kind;
    private $sessionId;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($id)
    {
        $this->userId = $id;
    }

    public function setPairedId($id)
    {
        $this->pairedId = $id;
    }

    public function getPairedId()
    {
        return $this->pairedId;
    }

    public function setPairingCode($code)
    {
        $this->pairingCode = $code;
    }

    public function getPairingCode()
    {
        return $this->pairingCode;
    }

    public function getCode1()
    {
        return $this->code_1;
    }

    public function setCode1($code)
    {
        $this->code_1 = $code;
    }

    public function getCode2()
    {
        return $this->code_2;
    }

    public function setCode2($code)
    {
        $this->code_2 = $code;
    }

    public function getCode3()
    {
        return $this->code_3;
    }

    public function setCode3($code)
    {
        $this->code_3 = $code;
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

    public function getBrothers()
    {
        return $this->brothers;
    }

    public function setBrothers($brothers)
    {
        $this->brothers = $brothers;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

}
