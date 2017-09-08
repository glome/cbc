<?php

namespace Application\Controllers;

class Redirect extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $shop->setCurrentProduct($request->getParameter('id'));
        $shop->forUser($this->user);
        $shop->registerBuy();
    }

    public function getRedeem($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $itinerary->forUser($this->user);
    }
}
