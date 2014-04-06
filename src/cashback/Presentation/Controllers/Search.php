<?php

namespace Application\Controllers;


class Search extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
    }


    public function postIndex($request)
    {

        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('search-field'));

        header('Location: /search');
        exit;

    }
}
