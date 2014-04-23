<?php

namespace Application\Common;

class RestMapper
{

    protected $cookieJar;


    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }


    protected function applyValuesToURL($url, $parameters)
    {
        return strtr($url, $parameters);
    }
}
