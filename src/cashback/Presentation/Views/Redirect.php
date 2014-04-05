<?php

namespace Application\Views;

class Redirect extends \Application\Common\View
{

    public function index()
    {
        $shop = $this->serviceFactory->create('Shop');
        $product = $shop->getCurrentProduct();

        $main = $this->templateBuilder->create('redirect');
        $main->assignAll([
            'product' => $product,
        ]);

        return $main->render();
    }
}
