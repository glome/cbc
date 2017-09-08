<?php

namespace Application\Controllers;

class Retailers extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);

        $shop->setPage($request->getParameter('page'));
        $shop->useCategory($request->getParameter('id'));
    }
}
