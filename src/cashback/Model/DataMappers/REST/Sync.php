<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class Sync extends \Application\Common\RestMapper
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

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );

        $id = $instance->getUserId();
        $kind = $instance->getKind();

        if ($id !== null) {
            $url = $this->applyValuesToURL($this->resources['user-pairing'], ['{id}' => $id ]);
            $request = $client->post(
                $this->host . $url,
                [],
                [
                    'application[apikey]' => $this->apikey,
                    'application[uid]' => $this->uid,
                    'synchronization[kind]' => $kind,
                ]
            );
            $response = $request->send();

            $data = $response->json();

            if (isset($data['error'])) {
                return false;
            }

            $instance->setPairingCode($data['code']);
            $_SESSION['glome.code'] = $data['code'];

            return true;
        }
    }

    public function store($instance)
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

        $id = $instance->getUserId();
        $url = $this->applyValuesToURL($this->resources['user-syncing'], ['{id}' => $id ]);

        $response = $client->post(
            $this->host . $url,
            [],
            [
                'pairing[code_1]' => $instance->getCode1(),
                'pairing[code_2]' => $instance->getCode2(),
                'pairing[code_3]' => $instance->getCode3(),
                'pairing[kind]'   => 'b'
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

    public function fetchBrothers($instance)
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

        $id = $instance->getUserId();
        if ($id !== null) {
            $url = $this->applyValuesToURL($this->resources['user-brothers'], ['{id}' => $id ]);
            $response = $client->get($this->host . $url)->send();

            $data = $response->json();

            if (isset($data['error'])) {
                return false;
            }

            $instance->setBrothers($data);
            return true;
        }
    }

    public function togglePairing($instance)
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
        $userId = $instance->getUserId();
        $url = $this->applyValuesToURL($this->resources['user-syncing-toggle'], [
          '{id}' => $userId,
          '{syncid}' => $id
        ]);

        $response = $client->post($this->host . $url, [], [])->send();

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
