<?php

namespace Application\Controllers;

class Catalog extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $this->checkLocked();

        $itinerary = $this->serviceFactory->create('Itinerary');
        $shop = $this->serviceFactory->create('Shop');

        $itinerary->forUser($this->user);
        $shop->forUser($this->user);
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
