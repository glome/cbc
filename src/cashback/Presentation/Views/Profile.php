<?php

namespace Application\Views;

class Profile extends \Application\Common\View
{

    private $currentTab = 'wishlist';

    public function index()
    {
        return $this->wishlist();
    }

    public function wishlist()
    {

        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();




        $builder = $this->templateBuilder;

        $main    = $builder->create('main');
        $content = $builder->create('profile');
        $footer  = $builder->create('footer');
        $profile = $builder->create('profile-brief');
        $deals   = $builder->create('deals');


        $content->assign('categories', $categories);
        $footer->assign('categories', $categories);
        $deals->assign('products', $shop->getRecommendations(4));

        $profile->assignAll([
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings(),
            'currency' => $configuration->getPreferredCurrency(),
        ]);

        $content->assignAll([
            'deals'    => $deals,
            'products' => $itinerary->getWishlist(),
            'history'  => $itinerary->getHistory(),
            'tab'      => $this->currentTab,
        ]);

        $main->assignAll([
            'content'  => $content,
            'user'     => $profile,
            'footer'   => $footer,
            'settings' => $settings,
        ]);
        return $main->render();
    }


    public function history()
    {
        $this->currentTab = 'history';
        return $this->wishlist();
    }


    public function wish()
    {

    }

    public function spurn()
    {

    }

}
