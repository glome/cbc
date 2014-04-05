<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $products = $shop->getProducts();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('catalog');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');

        $footer->assign('categories', $categories);
        $navigation->assign('categories', $categories);


        $content->assignAll([
            'category'   => $categories[$shop->getParentCategoryId()],
            'current'    => $shop->getCurrentCategoryId(),
            'navigation' => $navigation,
            'products'   => $products,
        ]);

        $main->assignAll([
            'content'    => $content,
            'footer'     => $footer,
            'settings'   => $settings,
            'user'       => $builder->create('profile-brief'),
        ]);
        return $main->render();
    }
}
