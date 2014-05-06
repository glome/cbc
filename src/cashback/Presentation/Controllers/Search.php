<?php

namespace Application\Controllers;

class Search extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
        $shop->setPage($request->getParameter('page'));
        $shop->forUser($user);
    }

    public function postIndex($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('search-field'));

        header('Location: /search');
        exit;
    }

    public function getAutocomplete($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('term'));
    }
}
