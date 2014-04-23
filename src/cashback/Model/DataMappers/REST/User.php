<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

class User extends \Application\Common\RestMapper
{

    private $host;
    private $apikey;
    private $uid;

    public function __construct($configuration)
    {
        $this->host = $configuration['rest']['host'];
        $this->apikey = $configuration['rest']['params']['application[apikey]'];
        $this->uid = $configuration['rest']['params']['application[uid]'];
        $this->resources = $configuration['rest']['resources'];

    }


    public function fetch($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        if ($instance->getId() === null) {
            $request = $client->post(
                $this->host . $this->resources['user'],
                [],
                [
                    'application[apikey]' => $this->apikey,
                    'application[uid]' => $this->uid
                ]
            );
            $response = $request->send();
            $data = $response->json();
            $instance->setId($data['glomeid']);
        }


        $request = $client->post(
            $this->host . $this->resources['user-login'],
            [],
            [
                'user[glomeid]' => $instance->getId(),
            ]
        );

        $response = $request->send();

        $temp = $response->getHeader('X-Csrf-Token')->toArray();
        $instance->setToken(array_pop($temp));


        foreach ($this->cookieJar->getMatchingCookies($request) as $cookie) {
            if ($cookie->getName() === '_session_id') {
                $instance->setSession($cookie->getValue());
                return;
            }
        }
    }
}
