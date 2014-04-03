<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

class User extends \Application\Common\CookieMapper
{

    public function fetch($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        if ($instance->getId() === null) {
            $request = $client->post('https://api.glome.me/users.json', [],
                [
                    'application[apikey]' => '275b4d4bec43c0812795de8a3765631d',
                    'application[uid]' => 'me.glome.demo.cbc'
                ]
            );
            $response = $request->send();
            $data = $response->json();
            $instance->setId($data['glomeid']);
        }


        $request = $client->post('https://api.glome.me/users/login.json', [],
            [
                'user[glomeid]' => $instance->getId(),
            ]
        );

        $response = $request->send();

        //var_dump($response->json());
        $temp = $response->getHeader('X-Csrf-Token')->toArray();
        $instance->setToken(array_pop($temp));

        foreach ($this->cookieJar->getMatchingCookies($request) as $cookie)
        {
            if ($cookie->getName() === '_session_id') {
                $instance->setSession($cookie->getValue());
                return;
            }
        }


    }


}