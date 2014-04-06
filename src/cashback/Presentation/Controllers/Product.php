<?php

namespace Application\Controllers;


class Product extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $shop = $this->serviceFactory->create('Shop');
        $shop->forUser($user);
        $itinerary->forUser($user);
        $shop->setCurrentProduct($request->getParameter('id'));

    }
}
