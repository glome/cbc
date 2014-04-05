<?php

namespace Application\Controllers;


class Catalog extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $shop = $this->serviceFactory->create('Shop');
        $shop->useCategory($request->getParameter('id'));
    }
}
