<?php

namespace Application\Views;

class Landing extends \Application\Common\View
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
        $content    = $builder->create('landing');
        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');
        $deals      = $builder->create('deals');

        $navigation->assignAll([
            'categories' => array_slice($categories, 0, 10),
            'noDropDown'  => true,
        ]);
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
            'about'           => $builder->create('about'),
            'deals'           => $deals,
            'banners'         => $builder->create('banners'),
            'navigation'      => $navigation,
            'popular'         => $builder->create('popular-categories'),
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
