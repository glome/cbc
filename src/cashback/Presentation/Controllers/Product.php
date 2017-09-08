<?php

namespace Application\Controllers;

class Product extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);
        $shop->setCurrentProduct($request->getParameter('id'));

        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->setSearchState(0);
    }
}
