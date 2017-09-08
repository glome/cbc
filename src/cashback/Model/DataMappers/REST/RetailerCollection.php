<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Event;

class RetailerCollection extends \Application\Common\RestMapper
{
    public function __construct($configuration)
    {
        parent::init($configuration);
    }

    public function fetch($collection)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($collection) {
                $event->stopPropagation();
            }
        );

        $struct = [
          '{page}' => $collection->getPage(),
          '{countries}' => $collection->getLocationQuery(),
        ];

        $cat_id = $collection->getCategoryId();

        if ($cat_id !== null) {
            $struct += [ '{cat_id}' => $cat_id ];
        }

        $url = $this->applyValuesToURL($this->resources['retailers'], $struct);
        $response = $client->get($this->host . $url)->send();
        $data = $response->json();

        foreach ($data as $entry) {
            $collection->addItem($entry);
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
