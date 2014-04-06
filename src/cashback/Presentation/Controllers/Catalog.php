<?php

namespace Application\Controllers;


class Catalog extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $shop = $this->serviceFactory->create('Shop');

        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
        $shop->forUser($user);
        $shop->useCategory($request->getParameter('id'));
    }
}