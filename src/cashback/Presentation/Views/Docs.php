<?php

namespace Application\Views;

class Docs extends \Application\Common\View
{

    public function index()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('search');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');
        $deals      = $builder->create('deals');

        $navigation->assign('categories', array_slice($categories, 0 , 10));
        $footer->assign('categories', $categories);
        $deals->assign('products', $shop->getRecommendations(4));


        $profile->assignAll([
            'wishes'     => $itinerary->getWishlistLength(),
            'earnings'   => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

        $content->assignAll([
            'navigation'      => $navigation,
            'results'         => $builder->create('doc-terms'),
        ]);

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

    public function termsOfUse()
    {
        return $this->index();
    }

    public function privacyPolicy()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('search');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');
        $deals      = $builder->create('deals');

        $navigation->assign('categories', array_slice($categories, 0 , 10));
        $footer->assign('categories', $categories);
        $deals->assign('products', $shop->getRecommendations(4));


        $profile->assignAll([
            'wishes'     => $itinerary->getWishlistLength(),
            'earnings'   => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

        $content->assignAll([
            'navigation'      => $navigation,
            'results'         => $builder->create('doc-privacy'),
        ]);

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
