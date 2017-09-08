<?php

namespace Application\Controllers;

class Docs extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);
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
