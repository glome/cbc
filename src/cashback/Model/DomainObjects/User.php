<?php

namespace Application\DomainObjects;

class User
{
    private $id = null;
    private $errorCode = 0;
    private $errorMessage = false;
    private $locked = false;
    private $forbidden = false;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    private $session = null;

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    private $token = null;

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    private $visitorId = null;

    public function setVisitorId($id)
    {
        $this->visitorId = $id;
    }

    public function getVisitorId()
    {
        return $this->visitorId;
    }

    public function getError()
    {
        return ['code' => $this->errorCode, 'message' => $this->errorMessage];
    }

    public function setError($code = 0, $message = '')
    {
        $this->setErrorCode($code);
        $this->setErrorMessage($message);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setErrorCode($code)
    {
        //echo "set user error: " . $code . "<br/>";
        $this->errorCode = $code;
        # TODO: get error codes from Glome
        switch ($code) {
            case 0:
              $this->locked = false;
              $this->forbidden = false;
              break;
            case 403:
              $this->forbidden = true;
              break;
            case 2301:
              $this->locked = true;
              break;
            default:
              $this->locked = false;
              $this->forbidden = false;
              break;
        }
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function isForbidden()
    {
        return $this->forbidden;
    }

    public function isLocked()
    {
        return $this->locked;
    }
}