<?php

namespace Application\Controllers;


class Profile extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $itinerary->forUser($user);
    }

    public function getWishlist($request)
    {
        $this->getIndex($request);
    }


    public function getHistory($request)
    {
        $this->getIndex($request);
    }


    public function getWish($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $itinerary->forUser($user);
        $itinerary->addWish($request->getParameter('id'));
    }

    public function getSpurn($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $itinerary->forUser($user);
        $itinerary->removeWish($request->getParameter('id'));
    }

}
