<?php

namespace Application\Views;

class Product extends \Application\Common\View
{

    public function index()
    {
        $builder = $this->templateBuilder;

        $main = $builder->create('main');
        $content = $builder->create('product');
        $navigation = $builder->create('navigation');
        $element = $builder->create('single-product');
        $overlays = $builder->create('overlays');


        $main->assignAll([
            'content' => $content,
            'user' => $builder->create('profile-brief'),
            'overlays' => $overlays,
        ]);

        $shop = $this->serviceFactory->create('Shop');
        $product = $shop->getCurrentProduct();

        $content->assignAll([
            'navigation' => $navigation,
            'details' => $element,
        ]);


        $overlays->assign('product', $product);
        $element->assign('product', $product);
        $categories = $shop->getCategories();
        $navigation->assign('categories', $categories);

        return $main->render();
    }
}
