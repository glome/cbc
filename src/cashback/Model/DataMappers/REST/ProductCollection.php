<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;


class ProductCollection extends \Application\Common\CookieMapper
{

    public function __construct($configuration)
    {
        $this->host = $configuration['rest']['host'];
        $this->apikey = $configuration['rest']['params']['application[apikey]'];
        $this->uid = $configuration['rest']['params']['application[uid]'];
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


        if ($collection->hasItems())
        {
            foreach ($collection as $id => $product) {
                $response = $client->get($this->host . "/products/{$product->getId()}.json")->send();
                $data = $response->json();
                if (isset($data['error'])) {
                    $collection->removeItem($id);
                    continue;
                }
                $this->applyParameter($product,$data);
            }
        }


        if ($collection->hasCategory()) {
            $cat = $collection->getCategory();
            $page = $collection->getPage();
            $response = $client->get($this->host . "/products.json?cat_id=$cat&per_page=20&page=$page")->send();
            $data = $response->json();
            foreach ($data as $id => $entry) {
                if (isset($data['error'])) {
                    $collection->removeItem($id);
                    continue;
                }
                $collection->addItem($entry);
            }
        }


        if ($collection->hasQuery()) {
            $query = $collection->getQuery();
            $page = $collection->getPage();
            $response = $client->get($this->host . "/products/search.json?keywords=$query&per_page=20&page=$page")->send();
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