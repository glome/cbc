<?php

namespace Application\Views;

class Catalog extends \Application\Common\View
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

        $products = $shop->getProducts();

        $categories = $shop->getCategories();
        $retailers = $shop->getCategoryRetailers();
        $selectedRetailers = $shop->getSelectedRetailers();
        $currentCategory = $shop->getParentCategoryId();

        $navigation = $builder->create('navigation');
        $footer     = $builder->create('footer');
        $profile    = $builder->create('profile-brief');

        $footer->assign('categories', $categories);
        $navigation->assignAll([
            'resource'   => 'catalog',
            'allcat_resource' => 'categories',
            'categories' => array_slice($categories, 0, 10),
            'searchType' => $configuration->getSearchState(),
        ]);

        $params = [
            'locked'          => $shop->getUser()->isLocked(),
            'navigation'      => $navigation,
            'category'        => null,
            'current'         => null,
            'products'        => [],
            'retailers'       => [],
            'selectedRetailers' => array_keys($selectedRetailers),
         ];

        if ($currentCategory !== null) {
            $params['category'] = $categories[$currentCategory];
            $params['current'] = $shop->getCurrentCategoryId();
            $params['products'] = $products;
            $params['retailers'] = $retailers;
        }

        if ($shop->hasReload()) {
            if ($currentCategory == null || count($products) == 0) {
                $content = $builder->create('no_results');
            } else {
                $content = $builder->create('product-list');
                $content->assign('products', $products);
            }
            $content->assignAll($params);

            return $content->render();
        }

        if ($shop->getPage() !== null) {
            $main = $builder->create('clean');
            if ($currentCategory == null || count($products) == 0) {
                $content = $builder->create('no_results');
            } else {
                $content = $builder->create('product-list');
                $content->assign('products', $products);
            }

            $content->assignAll($params);
            $main->assign('content', $content);

            return $main->render();
        }

        $main = $builder->create('main');

        if ($currentCategory == null || count($products) == 0) {
            $content = $builder->create('no_results');
        } else {
            $content = $builder->create('catalog');
        }

        $profile->assignAll([
            'locked' => $shop->getUser()->isLocked(),
            'wishes'   => $itinerary->getWishlistLength(),
            'earnings' => $itinerary->getEarnings('EUR'),
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
            ],
        ]);

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
            'locked'     => $shop->getUser()->isLocked(),
            'imtoken'    => $shop->getUser()->getMessagingToken()
        ]);

        return $main->render();
    }
}
