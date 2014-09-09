<?php

namespace Application\Controllers;

class Categories extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);

        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->setSearchState(0);
    }
}
