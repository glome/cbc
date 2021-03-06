<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;

class Product extends \Application\Common\RestMapper
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

        $id = $instance->getId();
        if ($id !== null) {
            $url = $this->applyValuesToURL($this->resources['product'], ['{id}' => $id]);
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();
            if (isset($data['error'])) {

                return false;
            }

            $this->applyParameter($instance, $data);
            $topCategory = $data['categories'][0];
            $instance->setCategoryId($topCategory['id']);
            return true;
        }
        return false;
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
