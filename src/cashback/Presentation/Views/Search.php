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

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();

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
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

        $content->assignAll([
            'navigation' => $navigation,
            'results'    => $results,
        ]);

        $navigation->assignAll([
            'categories' => array_slice($categories, 0 , 10),
            'query'      => $query,
        ]);

        $main->assignAll([
            'content'    => $content,
            'user'       => $profile,
            'footer'     => $footer,
            'settings'   => $settings,
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
                'language'    => $configuration->getPreferredLanguage(),
            ],
        ]);
        return $main->render();
    }


    public function autocomplete()
    {
        header('Content-Type: application/json');
        $shop = $this->serviceFactory->create('Shop');
        return json_encode(['suggestions' => $shop->getProductSuggestions()]);
    }

}
