<?php

namespace Application\DataMappers\Session;

class User extends \Application\Common\SessionMapper
{

    public function fetch($instance)
    {
        if (!isset($_SESSION['glome.session']) || !isset($_SESSION['glome.token'])) {
            return false;
        }

        $instance->setSession($_SESSION['glome.session']);
        $instance->setToken($_SESSION['glome.token']);
        if (isset($_SESSION['glome.pairing-code'])) {
            $instance->setPairingCode($_SESSION['glome.pairing-code']);
        }
        return true;
    }


    public function store($instance)
    {
        $value = $instance->getSession();
        if ($value !== null) {
            $_SESSION['glome.session'] = $value;
            $_SESSION['glome.token'] = $instance->getToken();
            $_SESSION['glome.pairing-code'] = $instance->getPairingCode();
        }
    }
}
