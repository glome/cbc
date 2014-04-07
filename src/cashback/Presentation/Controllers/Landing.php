<?php

namespace Application\Controllers;


class Landing extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $recognition = $this->serviceFactory->create('Recognition');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $shop = $this->serviceFactory->create('Shop');
        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
        $shop->forUser($user);
    }
}
