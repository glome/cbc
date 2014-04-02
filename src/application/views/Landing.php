<?php

namespace Application\Views;

class Landing extends \Application\Common\View
{

    public function index()
    {
        $container = $this->templateBuilder->create('main');
        $navigation = $this->templateBuilder->create('navigation');
        $container->assign('navigation', $navigation);

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();

        $navigation->assign('categories', array_slice($categories, 0, 9));
        return $container->render();
    }
}
