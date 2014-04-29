<?php

namespace Application\Views;

class Categories extends \Application\Common\View
{

    public function index()
    {
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();


        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();
        $query = $shop->getSearchedTerm();
        $products = $shop->getProducts();


        $builder = $this->templateBuilder;

        $main       = $builder->create('main');
        $content    = $builder->create('categories-container');
        $navigation = $builder->create('navigation');
        $listing    = $builder->create('categories');
        $footer     = $builder->create('footer');
        $profile = $builder->create('profile-brief');


        $groups = [];

        foreach ($categories as $key => $category) {
            $total = count($category['subcategories']);
            if ($total <= 9) {
                $categories[$key]['groups'] = $this->arrangeShort($category['subcategories']);
            } else {
                $categories[$key]['groups'] = $this->arrangeLong($category['subcategories'], $total);

            }
        }






        $listing->assign('categories', $categories);
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
            'categories'    => $listing,
        ]);

        $navigation->assignAll([
            'categories' => array_slice($categories, 0, 10),
            'noDropDown'  => true,
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


    protected function arrangeShort($list)
    {
        $i = 0;
        $groups = [];
        foreach ($list as $key => $value) {
            $n = (int)($i / 3);
            if (!isset($groups[$n])) {
                $groups[$n] = [];
            }
            $groups[$n][$key] = $value;
            $i += 1;
        }

        return $groups;
    }

    protected function arrangeLong($list, $total)
    {
        $groups = [];
        $a = ceil($total/3);
        $groups[0] = array_slice($list, 0, $a);
        $b = ceil(($total - $a)/2);
        $groups[1] = array_slice($list, $a, $b);
        $groups[2] = array_slice($list, $a + $b);
        return $groups;
    }
}
