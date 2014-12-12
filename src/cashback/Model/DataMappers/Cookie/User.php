<?php

namespace Application\DataMappers\Cookie;

class User
{
    public function fetch($instance)
    {
        if (!isset($_COOKIE['glomeid'])) {
            return false;
        }
        $instance->setId($_COOKIE['glomeid']);
        if (isset($_COOKIE['messaging'])) {
            $instance->setMessagingToken($_COOKIE['messaging']);
        }
        return true;

    }

    public function store($instance)
    {
        $id = $instance->getId();
        if ($id !== null) {
            setcookie('glomeid', $instance->getId(), time() + 157680000 /*5 years*/, '/', '', false, true);
        }
    }
}
