<?php

namespace Application\Views;

class Search extends \Application\Common\View
{

    public function index()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $query = $shop->getSearchedTerm();
        $products = $shop->getProducts();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('search');
        $navigation = $builder->create('navigation');
        $results    = $builder->create('results');
        $footer     = $builder->create('footer');
        $profile = $builder->create('profile-brief');


        $results->assign('products', $products);
        $footer->assign('categories', $categories);

        $profile->assignAll([
            'wishes' => $itinerary->getWishlistLength(),
        ]);

        $content->assignAll([
            'navigation' => $navigation,
            'results'    => $results,
        ]);

        $navigation->assignAll([
            'categories' => $categories,
            'query'      => $query,
        ]);

        $main->assignAll([
            'content'    => $content,
            'user'       => $profile,
            'footer'     => $footer,
            'settings'   => $settings,
        ]);
        return $main->render();
    }
}
