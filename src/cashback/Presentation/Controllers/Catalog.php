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

        $retailer = $request->getParameter('param');
        $isXHR = $request->getParameter('xhr');

        if (isset($isXHR)) {
            $shop->setReload();
        }

        if ($retailer) {
            $shop->toggleRetailer($retailer);
            exit;
        }

        $clear = $request->getParameter('clear');
        if (isset($clear)) {
            $shop->clearRetailers();
            exit;
        }
    }
}
