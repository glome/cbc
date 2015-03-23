<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;

class User extends \Application\Common\RestMapper
{
    public function __construct($configuration)
    {
        parent::init($configuration);
    }

    public function fetch($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );

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
                'application[apikey]' => $this->apikey,
                'application[uid]' => $this->uid,
                'user[glomeid]' => $instance->getId()
            ]
        );

        $response = $request->send();
        $data = $response->json();

        if ($response->hasHeader('X-Csrf-Token')) {
            $temp = $response->getHeader('X-Csrf-Token')->toArray();
            $instance->setToken(array_pop($temp));
        }
        else {
            if ($response->getStatusCode() == 404) {
                setcookie('glomeid', $instance->getId(), time() - 3600000);
                setcookie('messaging', '', time() - 3600000);
                header('Location: /');
                exit;
            }

            if ($instance->getId() && isset($data['error'])) {
                $instance->setErrorCode($data['code']);
                $instance->setErrorMessage($data['error']);
            }
        }

        if (! $instance->getMessagingToken() && isset($data['token'])) {
            $instance->setMessagingToken($data['token']);
            setcookie('messaging', $data['token'], time() + 157680000 /*5 years*/, '/', '', false, true);
        }

        foreach ($this->cookieJar->getMatchingCookies($request) as $cookie) {
            if ($cookie->getName() === '_session_id') {
                $instance->setSession($cookie->getValue());
                return;
            }
        }
    }

    public function update($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );

        $id = $instance->getId();
        $url = $this->applyValuesToURL($this->resources['user-profile'], ['{id}' => $id ], "PUT");

        $response = $client->put(
            $this->host . $url,
            [],
            [
                'application[apikey]' => $this->apikey,
                'application[uid]' => $this->uid,
                'user[locked_at]' => $instance->getLockedAt()
            ]
        )->send();

        $data = $response->json();

        if (isset($data['error']))
        {
            $instance->setErrorCode($data['code']);
            $instance->setErrorMessage($data['error']);
            return false;
        }

        return true;
    }
}
