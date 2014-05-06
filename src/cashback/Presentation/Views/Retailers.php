<?php

namespace Application\Views;

class Retailers extends \Application\Common\View
{
    public function index()
    {
        $builder = $this->templateBuilder;

        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();

        $categories = $shop->getCategories();
        $retailers = $shop->getRetailers();

        $main       = $builder->create('main');

        if (count($products) == 0) {
            $content = $builder->create('no_results');
        } else
        {
            $content = $builder->create('retailers');
        }

        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');

        $footer->assign('categories', $categories);
        $navigation->assignAll([
            'resource'   => 'retailers',
            'allcat_resource' => 'retailers',
            'categories' => array_slice($categories, 0, 10),
            'searchType' => $configuration->getSearchState(),
        ]);

        $profile->assignAll([
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

        $currentCategory = $shop->getParentCategoryId();

        $params = [
            'navigation'      => $navigation,
            'category'        => null,
            'current'         => null,
            'retailers'       => $retailers,
        ];

        if ($currentCategory !== null) {
            $params['category'] = $categories[$currentCategory];
            $params['current'] = $shop->getCurrentCategoryId();
        }

        $content->assignAll($params);

        $main->assignAll([
            'content'    => $content,
            'footer'     => $footer,
            'settings'   => $settings,
            'user'       => $profile,
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
                'language'    => $configuration->getPreferredLanguage(),
            ],
        ]);

        return $main->render();
    }
}
