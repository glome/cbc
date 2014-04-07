<?php

namespace Application\Controllers;


class Redirect extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $recognition = $this->serviceFactory->create('Recognition');
        $user = $recognition->getCurrentUser();

        $shop = $this->serviceFactory->create('Shop');
        $shop->setCurrentProduct($request->getParameter('id'));
        $shop->forUser($user);
        $shop->registerRedirect();
    }
}
