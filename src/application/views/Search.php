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
        $categories = $shop->getCategories();
        $navigation->assignAll([
            'categories' => $categories,
            'query' => $shop->getSearchedTerm(),
        ]);

        $results->assign('products', $shop->getProducts());
        $footer = $builder->create('footer');
        $footer->assign('categories', $categories);

        $main->assignAll([
            'content' => $content,
            'user' => $builder->create('profile-brief'),
            'footer'  => $footer,
        ]);


        return $main->render();
    }
}
