<?php

namespace Application\Views;

class Profile extends \Application\Common\View
{

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


        $content->assign('categories', $categories);
        $footer->assign('categories', $categories);


        $profile->assignAll([
            'wishes' => $itinerary->getWishlistLength(),
        ]);

        $content->assignAll([
            'deals'    => $builder->create('deals'),
            'products' => $itinerary->getWishlist(),
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
        return $this->wishlist();
    }


    public function wish()
    {

    }

    public function spurn()
    {

    }

}