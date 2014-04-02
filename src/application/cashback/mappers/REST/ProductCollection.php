<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

class ProductCollection extends \Application\Common\CookieMapper
{

    public function fetch($collection)
    {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $response = $client->get('https://api.glome.me/categories.json?display=flat')->send();
        $data = $response->json();

        var_dump($data);

        foreach ($data as $entry) {
           // $collection->addItem($entry);
        }


    }
}