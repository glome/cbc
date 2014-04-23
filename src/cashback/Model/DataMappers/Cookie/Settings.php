<?php

namespace Application\DataMappers\Cookie;

class Settings
{
    public function fetch($instance)
    {
        $all = $_COOKIE;

        foreach ($all as $key => $value) {
            if (strpos($key, 'config') === 0) {
                $instance->setParam(substr($key, 7), $value);
            }
        }
    }


    public function store($instance)
    {
        $params = $instance->getParams();
        foreach ($params as $key => $value) {
            setcookie("config.$key", $value, time() + 157680000 /*5 years*/, '/', '', false, true);
        }

    }
}
