<?php

namespace Application\Controllers;


class Profile extends \Application\Common\Controller
{
    public function getIndex($request)
    {

    }

    public function getWishlist($request)
    {

    }


    public function getHistory($request)
    {

    }


    public function getWish($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $itinerary->addWish($user, $request->getParameter('id'));
    }

    public function getSpurn($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $itinerary->removeWish($user, $request->getParameter('id'));
    }

}
