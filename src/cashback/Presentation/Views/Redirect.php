<?php

namespace Application\Views;

class Redirect extends \Application\Common\View
{

    public function index()
    {
        $shop = $this->serviceFactory->create('Shop');
        $product = $shop->getCurrentProduct($mustRegister = false);
        $redirect = $shop->getVisitDetails();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();

        $main = $this->templateBuilder->create('redirect');
        $main->assignAll([
            'product' => $product,
            'redirect' => $redirect,
        ]);

        return $main->render();
    }

    public function redeem()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $main = $this->templateBuilder->create('redeem');
        $message = $itinerary->getRedeemMessage();

        $main->assignAll([
            'link' => isset($message['url'])? $message['url'] : false,
        ]);

        return $main->render();
    }

}
