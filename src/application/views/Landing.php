<?php

namespace Application\Views;

class Landing extends \Application\Common\View
{

    public function index()
    {
        $builder = $this->templateBuilder;

        $main = $builder->create('main');
        $content = $builder->create('landing');
        $navigation = $builder->create('navigation');


        $content->assignAll([
            'navigation' => $navigation,
            'banners' => $builder->create('banners'),
            'deals' => $builder->create('deals'),
            'popular' => $builder->create('popular-categories'),
            'about' => $builder->create('about'),
        ]);

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $navigation->assign('categories', $categories);
        $footer = $builder->create('footer');
        $footer->assign('categories', $categories);


        $main->assignAll([
            'content' => $content,
            'user'    => $builder->create('profile-brief'),
            'footer'  => $footer,
        ]);
        return $main->render();
    }
}
