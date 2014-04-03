<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

class Category extends \Application\Common\CookieMapper
{

    public function fetch($instance)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $id = $instance->getId();
        if ($id !== null)
        {
            $response = $client->get("https://api.glome.me/categories/$id.json")->send();
            $data = $response->json();

            $this->applyParameter($instance,$data);
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