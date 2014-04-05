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

        $builder = $this->templateBuilder;
        $main = $builder->create('main');
        $content = $builder->create('profile');

        $content->assignAll([
            'deals' => $builder->create('deals'),
        ]);



        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $content->assign('categories', $categories);
        $footer = $builder->create('footer');
        $footer->assign('categories', $categories);

        $main->assignAll([
            'content' => $content,
            'user' => $builder->create('profile-brief'),
            'footer'  => $footer,
            'settings' => $settings,
        ]);
        return $main->render();
    }


    public function history()
    {
        return $this->wishlist();
    }

}
