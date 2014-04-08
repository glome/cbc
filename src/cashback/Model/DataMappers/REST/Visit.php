<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;


class Visit extends \Application\Common\CookieMapper
{

    public function __construct($configuration)
    {
        $this->host = $configuration['rest']['host'];
        $this->apikey = $configuration['rest']['params']['application[apikey]'];
        $this->uid = $configuration['rest']['params']['application[uid]'];
    }


    public function store($instance)
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

        $id = $instance->getProductId();
        if ($id !== null)
        {
            $user = $instance->getUserId();
            $response = $client->post($this->host . "/products/$id/getit.json", [],
            [
                'user[glomeid]' => $instance->getUserId(),
            ])->send();
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
            function(Event $event) use ($instance) {
                $event->stopPropagation();
            }
        );

        $id = $instance->getProductId();
        if ($id !== null)
        {
            $user = $instance->getUserId();
            $response = $client->get($this->host . "/products/$id/click/$user.json")->send();
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