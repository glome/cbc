<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class Finances extends \Application\Common\RestMapper
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
        //$client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );

        $id = $instance->getUserId();
        if ($id !== null) {
            $url = $this->applyValuesToURL(
                $this->resources['user-earnings'],
                ['{id}' => $id, '{apikey}' => $this->apikey, '{uid}' => $this->uid]
            );
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();
           // / print_r($data);
            $instance->setEarnings($data);
            if (isset($data['error'])) {
                $instance->setError($data['error']);
            }
        }
    }
}
