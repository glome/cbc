<?php

namespace Application\Views;

class Landing extends \Application\Common\View
{

    public function index()
    {
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('landing');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');


        $navigation->assign('categories', $categories);
        $footer->assign('categories', $categories);


        $content->assignAll([
            'about'      => $builder->create('about'),
            'deals'      => $builder->create('deals'),
            'banners'    => $builder->create('banners'),
            'navigation' => $navigation,
            'popular'    => $builder->create('popular-categories'),
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
