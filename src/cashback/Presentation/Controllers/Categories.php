<?php

namespace Application\Controllers;


class Categories extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
        $shop->forUser($user);
    }


}
