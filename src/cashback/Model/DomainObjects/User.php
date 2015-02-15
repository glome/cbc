<?php

namespace Application\DomainObjects;

class User
{
    private $id = null;
    private $errorCode = 0;
    private $errorMessage = false;
    private $locked = false;
    private $forbidden = false;
    private $session = null;
    private $token = null;
    private $visitorId = null;
    private $locked_at = false;
    // this is for IM purposes
    private $messagingToken = null;
    private $unlockCode = null;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

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

    public function getLockedAt()
    {
        return $this->locked_at;
    }

    public function setLockedAt($value)
    {
        $this->locked_at = value;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function unlock()
    {
        $this->locked = false;
    }

    public function getMessagingToken()
    {
        // ugly
        $ret = $this->messagingToken;
        if ($ret == null)
        {
            $ret = $_COOKIE['messaging'];
        }
        return $ret;
    }

    public function setMessagingToken($token)
    {
        $this->messagingToken = $token;
    }

    public function getUnlockCode()
    {
        return $this->unlockCode;
    }

    public function setUnlockCode($code)
    {
        $this->unlockCode = $code;
    }
}