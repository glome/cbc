<?php

namespace Application\Controllers;


class Product extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $shop->setCurrentProduct($request->getParameter('id'));

    }
}
