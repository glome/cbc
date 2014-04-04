<?php

namespace Application\Views;

class Redirect extends \Application\Common\View
{

    public function index()
    {
        $builder = $this->templateBuilder;

        $main = $builder->create('redirect');
        $shop = $this->serviceFactory->create('Shop');
        $product = $shop->getCurrentProduct();
        $main->assignAll([
            'product' => $product,
        ]);

        return $main->render();
    }
}
