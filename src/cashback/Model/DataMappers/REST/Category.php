<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;

class Category extends \Application\Common\RestMapper
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

        $glomeid = null;

        if ($instance->user) {
          $glomeid = $instance->user->getId();
        }
        $id = $instance->getId();

        if ($glomeid !== null and $id !== null) {
            $url = $this->applyValuesToURL($this->resources['category'], ['{glomeid}' => $glomeid, '{id}' => $id]);
            $response = $client->get($this->host . $url)->send();
            $data = $response->json();
            if (isset($data['error'])) {
                return false;
            }
            $this->applyParameter($instance, $data);
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
