<?php

    namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;

    class CategoryCollection extends \Application\Common\CookieMapper
    {

        public function fetch($collection)
        {
            $cookiePlugin = new CookiePlugin($this->cookieJar);

            $client = new Client;
            $client->addSubscriber($cookiePlugin);

            $id = $collection->getId();

            if ($id === null) {
                $response = $client->get('https://api.glome.me/categories.json?selector=a')->send();
                $data = $response->json();
                foreach ($data as $entry) {
                    $collection->addItem($entry);
                }
            } else {
                $response = $client->get("https://api.glome.me/categories/$id.json?selector=a")->send();
                $data = $response->json();
                foreach ($data['children'] as $item) {
                    $response = $client->get("https://api.glome.me/categories/{$item['id']}.json?selector=a")->send();
                    $entry = $response->json();
                    $collection->addItem($entry);
                }
            }
        }

        public function fetch2($collection) {
            $cookiePlugin = new CookiePlugin($this->cookieJar);

            $client = new Client;
            $client->addSubscriber($cookiePlugin);

            if (isset($_COOKIE['test'])) {
                $response = $client->get('https://api.glome.me/categories.json?selector=a')->send();
                $data = $response->json();
                $list = $this->collectIdValues($data);
                $response = $client->get('https://api.glome.me/categories.json?display=tree&filter=all&selector=a')->send();
                $data = $response->json();

                foreach ($data as $item) {
                    if (in_array($item['id'], $list)) {
                        $this->addSubcategories($collection, $item);
                    }
                }

              //  var_dump($collection);
            }



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