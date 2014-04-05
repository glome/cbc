<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;

class CategoryCollection extends \Application\Common\CookieMapper
{

    public function fetch($collection) {
        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $list = $this->fetchTopLevelCategories($client);

        $response = $client->get('https://api.glome.me/categories.json?display=tree&filter=all&selector=a')->send();
        $data = $response->json();

        foreach ($data as $item) {
            if (in_array($item['id'], $list)) {
                $this->addSubcategories($collection, $item);
            }
        }
    }

    public function fetchTopLevelCategories($client)
    {
        $response = $client->get('https://api.glome.me/categories.json?selector=a')->send();
        $data = $response->json();
        return $this->collectIdValues($data);
    }


    private function collectIdValues($data)
    {
        $list = [];
        foreach ($data as $item) {
            $list[] = $item['id'];
        }
        return $list;
    }


    public function addSubcategories($categories, $data){
        $current = $categories->addItem($data);
        foreach ($data['children'] as $item) {
            $current->addItem($item);
        }
    }

}