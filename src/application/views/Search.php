<?php

namespace Application\Views;

class Search extends \Application\Common\View
{

    public function index()
    {
        $builder = $this->templateBuilder;

        $main = $builder->create('main');
        $content = $builder->create('search');
        $navigation = $builder->create('navigation');
        $results = $builder->create('results');

        $content->assignAll([
            'navigation' => $navigation,
            'results' => $results,
        ]);

        $shop = $this->serviceFactory->create('Shop');
        $navigation->assignAll([
            'categories' => $shop->getCategories(),
            'query' => $shop->getSearchedTerm(),
        ]);

        $results->assign('products', $shop->getProducts());

        $main->assignAll([
            'content' => $content,
            'user' => $builder->create('profile-brief'),
        ]);


        return $main->render();
    }
}
