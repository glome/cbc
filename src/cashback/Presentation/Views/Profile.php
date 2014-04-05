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

        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();


        $builder = $this->templateBuilder;

        $main    = $builder->create('main');
        $content = $builder->create('profile');
        $footer  = $builder->create('footer');


        $content->assign('categories', $categories);
        $footer->assign('categories', $categories);


        $content->assignAll([
            'deals'    => $builder->create('deals'),
        ]);

        $main->assignAll([
            'content'  => $content,
            'user'     => $builder->create('profile-brief'),
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
