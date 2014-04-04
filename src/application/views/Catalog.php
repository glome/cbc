<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {
        $builder = $this->templateBuilder;

        $main = $builder->create('main');
        $content = $builder->create('catalog');
        $navigation = $builder->create('navigation');


        $shop = $this->serviceFactory->create('Shop');
        $products = $shop->getProducts();
        $categories = $shop->getCategories();
        $navigation->assign('categories', $categories);
        $footer = $builder->create('footer');
        $footer->assign('categories', $categories);

        $current = $shop->getParentCategoryId();

        $content->assignAll([
            'navigation' => $navigation,
            'products'   => $products,
            'category'   => $categories[$current],
            'current'    => $shop->getCurrentCategoryId(),
        ]);


        $main->assignAll([
            'content' => $content,
            'user'    => $builder->create('profile-brief'),
            'footer'  => $footer,
        ]);
        return $main->render();
    }
}
