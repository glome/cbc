<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
{

    public function index()
    {

        $builder = $this->templateBuilder;

        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();

        $products = $shop->getProducts();


        if ($shop->getPage() !== null ) {
            $main       = $builder->create('clean');
            $content    = $builder->create('product-list');
            $main->assign('content', $content);
            $content->assign('products', $products);
            return $main->render();
        }


        $categories = $shop->getCategories();
        $retailers = $shop->getCategoryRetailers();




        $main       = $builder->create('main');
        $content    = $builder->create('catalog');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');

        $footer->assign('categories', $categories);
        $navigation->assign('categories', array_slice($categories, 0 , 10));


        $profile->assignAll([
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
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
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
                'language'    => $configuration->getPreferredLanguage(),
            ],
        ]);
        return $main->render();
    }
}
