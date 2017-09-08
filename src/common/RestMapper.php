<?php

namespace Application\Common;

class RestMapper
{
    protected $host;
    protected $apikey;
    protected $uid;
    protected $resources;
    protected $cookieJar;

    protected function init($configuration)
    {
        $this->host = $configuration['rest']['host'];
        $this->apikey = $configuration['rest']['params']['application[apikey]'];
        $this->uid = $configuration['rest']['params']['application[uid]'];
        $this->resources = $configuration['rest']['resources'];
    }

    protected function applyValuesToURL($url, $parameters, $method = "GET")
    {
        $ret = strtr($url, $parameters);
        if ($method == "GET") {
            $ret .= "&application[apikey]=" . $this->apikey . "&application[uid]=" . $this->uid;
        }
        return $ret;
    }

    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }
}
