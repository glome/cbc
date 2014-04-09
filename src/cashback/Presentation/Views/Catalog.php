<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {

        $time = microtime(true);

        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');

        if ($shop->getPage() !== null ) { var_dump(111111111111); }

        $categories = ($shop->getPage() === null ) ? $shop->getCategories() : [];
        $products = $shop->getProducts();
        $retailers = ($shop->getPage() === null ) ? $shop->getCategoryRetailers() : [];




        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('catalog');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');

        $footer->assign('categories', $categories);
        $navigation->assign('categories', array_slice($categories, 0 , 10));


        $profile->assignAll([
            'wishes'   => ($shop->getPage() === null ) ? $itinerary->getWishlistLength() : '',
            'earnings' => ($shop->getPage() === null ) ? $itinerary->getEarnings('EUR') : '',
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
            $params['category'] = isset($categories[$currentCategory]) ? $categories[$currentCategory] : null;
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
