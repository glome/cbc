<?php

namespace Application\DataMappers\REST;

use Guzzle\Http\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Common\Event;

class CategoryCollection extends \Application\Common\RestMapper
{
    public function __construct($configuration)
    {
        parent::init($configuration);
    }

    public function fetch($collection)
    {
        $time = microtime(true);

        $cookiePlugin = new CookiePlugin($this->cookieJar);

        $client = new Client;
        $client->addSubscriber($cookiePlugin);

        $client->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) use ($collection) {
                $event->stopPropagation();
            }
        );

        $glomeid = null;

        if ($collection->user) {
          $glomeid = $collection->user->getId();
        }

        if ($glomeid !== null) {
          $url = $this->applyValuesToURL($this->resources['categories'], ['{glomeid}' => $glomeid]);
          $response = $client->get($this->host . $url)->send();

          $data = $response->json();

          if (isset($data['error']))
          {
              $collection->setErrorCode($data['code']);
              $collection->setErrorMessage($data['error']);
          }

          foreach ($data as $item) {
              $this->addSubcategories($collection, $item);
          }
        }

        $time = microtime(true) - $time;
    }

    public function addSubcategories($categories, $data)
    {
        $current = $categories->addItem($data);
        if (!isset($data['children'])) {
            return;
        }
        foreach ($data['children'] as $item) {
            $current->addItem($item);
        }
    }
}
