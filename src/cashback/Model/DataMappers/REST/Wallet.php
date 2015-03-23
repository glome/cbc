<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class Wallet extends \Application\Common\RestMapper
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

        $id = $instance->getUserId();
        if ($id !== null) {
            $url = $this->applyValuesToURL($this->resources['user-redeem'], ['{id}' => $id]);
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();
            $instance->setResponse($data);
        }
    }
}
