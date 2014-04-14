<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class RetailerCollection extends \Application\Common\RestMapper
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


    public function fetch($collection)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function(Event $event) use ($collection) {
                $event->stopPropagation();
            }
        );



        $id = $collection->getCategoryId();

        if ($id !== null)
        {
            $url = $this->applyValuesToURL($this->resources['categories-retailers'], ['{id}' => $id ]);
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();
            foreach ($data as $entry) {
                $collection->addItem($entry);
            }
        }
    }


    private function applyParameter($instance, $parameters)
    {
        foreach ($parameters as $key => $value) {
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }
    }

}