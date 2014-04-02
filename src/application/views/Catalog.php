<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {
        $container = $this->templateBuilder->create('catalog');

        $shop = $this->serviceFactory->create('Shop');
        $products = $shop->getProducts();

        $navigation = $this->templateBuilder->create('navigation');
        $container->assign('navigation', $navigation);
        return $container->render();
    }
}
