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
        //$instance->setId('cd_65631d_1e12f98457926c4f06cc13f670ef09e5');
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
