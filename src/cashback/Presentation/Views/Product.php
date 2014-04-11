<?php

namespace Application\Views;

class Product extends \Application\Common\View
{

    public function index()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $product = $shop->getCurrentProduct();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('product');
        $navigation = $builder->create('navigation');
        $element    = $builder->create('single-product');
        $overlays   = $builder->create('overlays');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');


        $tw_url = 'http' . (isset($_SERVER['HTTPS'])? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/product/' . $product['id'];
        $tw_text = urlencode('Best purchase on Glome Cashback.');


        $navigation->assign('categories', array_slice($categories, 0 , 10));
        $overlays->assign('product', $product);
        $element->assignAll([
            'product' => $product,
            'products'   => $shop->getRecommendations(4),
            'tw_url' =>   $tw_url,
            'tw_text' =>   $tw_text,

        ]);
        $footer->assign('categories', $categories);

        $profile->assignAll([
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

        $content->assignAll([
            'details'    => $element,
            'navigation' => $navigation,
        ]);

        $main->assignAll([
            'content'       => $content,
            'footer'        => $footer,
            'overlays'      => $overlays,
            'settings'      => $settings,
            'user'          => $profile,
            'isProductPage' => true,
            'fb_link'       => $tw_url,
            'fb_image'      => $product['image'],
            'fb_title'      => 'cutomizable longer description .. with words',
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);
        return $main->render();
    }
}
