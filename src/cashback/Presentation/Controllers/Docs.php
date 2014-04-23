<?php

namespace Application\Controllers;


class Docs extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();
        $shop = $this->serviceFactory->create('Shop');
        $shop->forUser($user);

        $itinerary->forUser($user);
    }


    public function getTermsOfUse($request)
    {
        $this->getIndex($request);
    }

    public function getPrivacyPolicy($request)
    {
        $this->getIndex($request);
    }
}
