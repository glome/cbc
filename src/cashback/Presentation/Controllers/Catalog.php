<?php

namespace Application\Controllers;

class Catalog extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $recognition = $this->serviceFactory->create('Recognition');
        $shop = $this->serviceFactory->create('Shop');

        $user = $recognition->getCurrentUser();
        $itinerary->forUser($user);
        $shop->forUser($user);
        $shop->setPage($request->getParameter('page'));
        $shop->useCategory($request->getParameter('id'));

        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->setSearchState(1);
        $configuration->setCurrentCategory($request->getParameter('id'));

        $shop->clearQuery();

        $isXHR = $request->getParameter('xhr');
        if (isset($isXHR)) {
            $shop->setReload();
        }

        $clear = $request->getParameter('clear');
        if (isset($clear)) {
            $shop->clearRetailers();
            exit;
        }

        $retailer = $request->getParameter('param');
        if (isset($retailer)) {
            $shop->toggleRetailer($retailer);
            exit;
        }
    }
}
