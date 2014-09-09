<?php

namespace Application\Controllers;

class Search extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);

        $shop->setPage($request->getParameter('page'));
    }

    public function postIndex($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('search-field'));

        header('Location: /search');
        exit;
    }

    public function getAutocomplete($request)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('term'));
    }
}
