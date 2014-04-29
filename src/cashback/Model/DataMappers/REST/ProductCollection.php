<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Common\Event;

class ProductCollection extends \Application\Common\RestMapper
{

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
            function (Event $event) use ($collection) {
                $event->stopPropagation();
            }
        );




        if ($collection->hasItems()) {
            foreach ($collection as $id => $product) {

                $url = $this->applyValuesToURL($this->resources['product'], ['{id}' => $product->getId() ]);
                $response = $client->get($this->host . $url)->send();
                $data = $response->json();
                if (isset($data['error'])) {
                    $collection->removeItem($id);
                    continue;
                }
                $this->applyParameter($product, $data);
            }
        } elseif ($collection->hasCategory() && $collection->hasQuery()) {


            $resKey = 'products';
            if ($collection->getAdvertisers() !== '') {
                $resKey = 'search-category';
            }
            $url = $this->applyValuesToURL($this->resources[$resKey], [
                '{id}'       => $collection->getCategory(),
                '{page}'     => $collection->getPage(),
                '{currency}' => $collection->getCurrency(),
                '{countries}'=> $collection->getLocationQuery(),
                '{query}'    => $collection->getQuery(),
            ]);

            $response = $client->get($this->host . $url)->send();
            $data = $response->json();

            foreach ($data as $id => $entry) {
                $collection->addItem($entry);
            }
        } elseif ($collection->hasCategory() && !$collection->hasQuery()) {


            $resKey = 'products';
            if ($collection->getAdvertisers() !== '') {
                $resKey = 'products-with-advertisers';
            }
            $url = $this->applyValuesToURL($this->resources[$resKey], [
                '{id}'       => $collection->getCategory(),
                '{page}'     => $collection->getPage(),
                '{currency}' => $collection->getCurrency(),
                '{order}'    => $collection->getOrder() ? 'desc':'asc',
                '{adv}'      => $collection->getAdvertisers(),
                '{countries}'=> $collection->getLocationQuery(),
            ]);

            $response = $client->get($this->host . $url)->send();
            $data = $response->json();

            foreach ($data as $id => $entry) {
                $collection->addItem($entry);
            }
        } elseif ($collection->hasQuery()) {



            $url = $collection->forAutocomplete() ? $this->resources['search-suggestions'] : $this->resources['search'];

            $url = $this->applyValuesToURL(
                $url,
                [
                    '{query}' => $collection->getQuery(),
                    '{page}' => $collection->getPage(),
                ]
            );

            $response = $client->get($this->host . $url)->send();
            $data = $response->json();

            if (isset($data['error'])) {
                return;
            }
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
