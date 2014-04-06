<?php

namespace Application\Controllers;


class Landing extends \Application\Common\Controller
{
    public function getIndex($request)
    {

        $recognition = $this->serviceFactory->create('Recognition');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);


    }
}
