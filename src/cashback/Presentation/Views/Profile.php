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
        $recognition = $this->serviceFactory->create('Recognition');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();



        $builder = $this->templateBuilder;

        $main    = $builder->create('main');
        $content = $builder->create('profile');
        $footer  = $builder->create('footer');
        $profile = $builder->create('profile-brief');
        $deals   = $builder->create('deals');


        $content->assign('categories', array_slice($categories, 0 , 10));
        $footer->assign('categories', $categories);
        $deals->assign('products', $shop->getRecommendations(4));


        $code = $recognition->getPairingCode();

        $profile->assignAll([
            'wishes'        => $itinerary->getWishlistLength(),
            'earnings'      => $itinerary->getEarnings('EUR'),
            'currencies'    => $configuration->getListOf('currencies'),
            'current'       => [
                'currency'  => $configuration->getPreferredCurrency(),
            ],
            'total'         => $itinerary->getTotalEarnings(),
            'opened'        => true,
        ]);

        $content->assignAll([
            'deals'      => $deals,
            'products'   => $itinerary->getWishlist(),
            'history'    => $itinerary->getHistory('EUR'),
            'currency'   => $configuration->getPreferredCurrency(),
            'tab'        => $this->currentTab,
            'code'       => str_split($code, 4),
        ]);



        $main->assignAll([
            'content'  => $content,
            'user'     => $profile,
            'footer'   => $footer,
            'settings' => $settings,
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
                'language'    => $configuration->getPreferredLanguage(),
            ],
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


    public function redeem()
    {
        $this->currentTab = 'redeem';
        return $this->wishlist();
    }

}
