<?php

namespace Application\DomainObjects;

class User
{
    private $id = null;

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

    private $pairingCode;

    public function setPairingCode($code)
    {
        $this->pairingCode = $code;
    }

    public function getPairingCode()
    {
        return $this->pairingCode;
    }
}
