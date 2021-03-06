<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;

class Visit extends \Application\Common\RestMapper
{
    public function __construct($configuration)
    {
        parent::init($configuration);
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

        $id = $instance->getProductId();
        if ($id !== null) {

            $url = $this->applyValuesToURL($this->resources['product-visit'], ['{id}' => $instance->getProductId()], "POST");
            $response = $client->post(
                $this->host . $url,
                [],
                [
                    'application[apikey]' => $this->apikey,
                    'application[uid]' => $this->uid,
                    'user[glomeid]' => $instance->getUserId(),
                ]
            )->send();

            $data = $response->json();

            if (isset($data['error'])) {

                return false;
            }

            return true;
        }
        return false;
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

        $id = $instance->getProductId();
        if ($id !== null) {
            $user = $instance->getUserId();
            $url = $this->applyValuesToURL($this->resources['product-buy'],
              [
                '{id}' => $instance->getProductId(),
                '{user}' => $instance->getUserId()
              ]
            );
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();

            if (isset($data['error'])) {
                return false;
            }

            $instance->setTrackingLink($data['url']);

            return true;
        }
        return false;
    }
}

