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
        $product = $builder->create('single-product');


        $content->assignAll([
            'navigation' => $navigation,
            'details' => $product,
        ]);


        $main->assignAll([
            'content' => $content,
            'user' => $builder->create('profile-brief'),
        ]);

        $shop = $this->serviceFactory->create('Shop');
        $product->assign('product', $shop->getCurrentProduct());
        $categories = $shop->getCategories();
        $navigation->assign('categories', $categories);

        return $main->render();
    }
}
