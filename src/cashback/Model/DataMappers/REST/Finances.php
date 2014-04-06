<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class Finances extends \Application\Common\CookieMapper
{

    private $host;
    private $apikey;
    private $uid;

    public function __construct($configuration)
    {
        $this->host = $configuration['rest']['host'];
        $this->apikey = $configuration['rest']['params']['application[apikey]'];
        $this->uid = $configuration['rest']['params']['application[uid]'];
    }


    public function fetch($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function(Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );
        $id = $instance->getUserId();
        if ($id !== null)
        {
            $response = $client->get($this->host . "/users/$id/earnings.json")->send();
            $data = $response->json();
            if (isset($data['error']))
            {
                $instance->setError($data['error']);
            }

        }


    }


}