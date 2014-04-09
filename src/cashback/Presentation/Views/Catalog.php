<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $products = $shop->getProducts();
        $retailers = $shop->getCategoryRetailers();




        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('catalog');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');

        $footer->assign('categories', $categories);
        $navigation->assign('categories', $categories);


        $profile->assignAll([
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currency' => $configuration->getPreferredCurrency(),
        ]);

        $currentCategory = $shop->getParentCategoryId();

        $params = [
            'navigation'      => $navigation,
            'category'        => null,
            'current'         => null,
            'products'        => [],
            'retailers'       => [],
        ];

        if ($currentCategory !== null) {
            $params['category'] = $categories[$currentCategory];
            $params['current'] = $shop->getCurrentCategoryId();
            $params['products'] = $products;
            $params['retailers'] = $retailers;
        }

        $content->assignAll($params);


        $main->assignAll([
            'content'    => $content,
            'footer'     => $footer,
            'settings'   => $settings,
            'user'       => $profile,
        ]);
        return $main->render();
    }
}
