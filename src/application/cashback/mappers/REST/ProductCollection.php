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

        if ($collection->hasCategory()) {
            $cat = $collection->getCategory();
            $response = $client->get("https://api.glome.me/products.json?cat_id=$cat")->send();
            $data = $response->json();
            foreach ($data as $entry) {
                $collection->addItem($entry);
            }
        }


        if ($collection->hasQuery()) {
            $query = $collection->getQuery();
            $response = $client->get("https://api.glome.me/products/search.json?keywords=$query&per_page=20&page=1")->send();
            $data = $response->json();
            if (isset($data['status']) && $data['status'] === 1){
                return;
            }
            foreach ($data as $entry) {
                $collection->addItem($entry);
            }
        }

        //$response = $client->get('https://api.glome.me/categories.json?display=flat')->send();
        //$data = $response->json();

      //  var_dump($data);



    }
}