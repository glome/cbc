<?php

namespace Application\Services;


class Shop extends \Application\Common\Service
{

    private $currentCategoryId = null;
    private $currentProductId = null;
    private $categoryTree = null;
    private $currentUser = null;
    private $currentProduct = null;
    private $currentPage = null;
    private $currentQuery = null;
    private $visit = null;
    private $isReload = false;


    public function setReload()
    {
        $this->isReload = true;
    }

    public function hasReload()
    {
        return $this->isReload;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
    }


    public function getPage()
    {
        return $this->currentPage;
    }

    public function forUser($user)
    {
        $this->currentUser = $user;
    }

    public function useCategory($id)
    {
        $this->currentCategoryId = $id;
    }

    public function getCurrentCategoryId()
    {
        return $this->currentCategoryId;
    }

    public function useProduct($id)
    {
        $this->currentProductId = $id;
    }

    public function getCategories()
    {
        if ($this->categoryTree === null) {
            $this->categoryTree = $this->collectCategoryTree();
        }
        return $this->categoryTree;
    }


    private function collectCategoryTree()
    {
        $categories = $this->domainObjectFactory->create('CategoryCollection');
        $session = $this->dataMapperFactory->create('CategoryCollection', 'Session');

        if (!$session->fetch($categories)) {
            $api = $this->dataMapperFactory->create('CategoryCollection', 'REST');
            $api->fetch($categories);
            $session->store($categories);
        }

        return $categories->getParsedArray();
    }


    public function getParentCategoryId($id = null)
    {
        if ($id === null) {
            $id = $this->currentCategoryId;
        }

        $category = $this->domainObjectFactory->create('CategoryCollection');
        $category->setId($id);
        $api = $this->dataMapperFactory->create('Category', 'REST');

        $session = $this->dataMapperFactory->create('Category', 'Session');
        if (!$session->fetch($category)) {
            $api->fetch($category);
        }

        return $category->getParentId();
    }


    public function getProducts()
    {

        $settings = $this->domainObjectFactory->create('Settings');

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);


        $locations = $settings->getLocations();
        $locations = array_keys($locations);


        $retailers = $this->getSelectedRetailers();

        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setCategory($this->currentCategoryId);
        $products->setLocations($locations);


        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->fetch($products);

        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');
        $products->setPage($this->currentPage);
        $products->setLanguage($settings->getLanguage());
        $products->setCurrency($settings->getCurrency());
        $products->setOrder($settings->getOrder());
        $products->setAdvertisers(array_keys($retailers));


        $api->fetch($products);

        $incentives =  $this->domainObjectFactory->create('IncentiveCollection');
        $api = $this->dataMapperFactory->create('IncentiveCollection', 'REST');
        $api->fetch($incentives);

        $products->applyIncentives($incentives);

        $db = $this->dataMapperFactory->create('ProductCollection', 'SQL');
        $products->setUserId($this->currentUser->getId());
        $db->fetch($products);

        if ($products->hasItems()) {
            $db = $this->dataMapperFactory->create('RecommendationCollection', 'SQL');
            $db->fetch($products);
        }


        return $products->getParsedArray();
    }


    public function clearQuery()
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setQuery('');
        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->store($products);
    }


    public function prepareSearch($query)
    {
        $this->currentQuery = $query;
        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setQuery($query);
        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->store($products);
    }


    public function getProductSuggestions()
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setQuery($this->currentQuery);
        $products->forAutocomplete(true);
        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');
        $api->fetch($products);

        $list = [];
        foreach($products as $id => $product) {
            $list[] = ['id' => $id, 'value' =>  $product->getTitle() ,'label' =>  $product->getTitle()];
        }

        return $list;

    }



    public function getSearchedTerm()
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->fetch($products);


        $db = $this->dataMapperFactory->create('RecommendationCollection', 'SQL');
        $db->fetch($products);

        $term = $products->getQuery();
        return $term !== null ? $term : '';
    }



    public function setCurrentProduct($productId)
    {
        $this->currentProductId = $productId;
    }

    public function getCurrentProduct($mustRegister = true)
    {
        if ($this->currentProduct === null ) {
            $this->currentProduct = $this->acquireProduct();
        }

        $product = $this->currentProduct;

        $db = $this->dataMapperFactory->create('Wish', 'SQL');
        $wish = $this->domainObjectFactory->create('Wish');
        $wish->setUserId($this->currentUser->getId());
        $wish->setProductId($product->getId());

        if ($db->fetch($wish))
        {
            $product->markAsFavorite();
        }

        if ($mustRegister) {
            $this->registerVisitFor($product);
        }


        $incentives =  $this->domainObjectFactory->create('IncentiveCollection');
        $api = $this->dataMapperFactory->create('IncentiveCollection', 'REST');
        $api->fetch($incentives);

        $incentive = $incentives->locateIncentive($product->getIncentiveId());

        $product->applyIncentive($incentive);


        $this->currentCategoryId = $product->getCategoryId();
        $retailers = $this->getCategoryRetailers();
        $product->associateRetailer($retailers);


        return $product->getParsedArray();
    }


    private function acquireVisit($product)
    {
        $visit = $this->domainObjectFactory->create('Visit');
        $visit->setCategoryId($product->getCategoryId());
        $visit->setProductId($product->getId());
        $visit->setUserId($this->currentUser->getId());
        return $visit;
    }


    private function registerVisitFor($product)
    {

        $visit = $this->acquireVisit($product);


        $session = $this->dataMapperFactory->create('Visit', 'Session');
        if (!$session->fetch($visit)) {

            $db = $this->dataMapperFactory->create('Visitor', 'SQL');
            $db->store($this->currentUser);

            $visit->setVisitorId($this->currentUser->getVisitorId());

            $db = $this->dataMapperFactory->create('Visit', 'SQL');
            $db->store($visit);

            $session->store($visit);
        }
        $api = $this->dataMapperFactory->create('Visit', 'REST');
        $api->store($visit);

    }



    public function getCategoryRetailers()
    {
        $id = $this->currentCategoryId;
        $retailers = $this->domainObjectFactory->create('RetailerCollection');
        $retailers->setCategoryId($id);


        $settings = $this->domainObjectFactory->create('Settings');

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);


        $locations = $settings->getLocations();
        $locations = array_keys($locations);

        $retailers->setLocations($locations);

        $api = $this->dataMapperFactory->create('RetailerCollection', 'REST');
        $api->fetch($retailers);

        return $retailers->getParsedArray();

    }

    public function getSelectedRetailers()
    {
        $retailers = $this->domainObjectFactory->create('RetailerCollection');
        $session = $this->dataMapperFactory->create('RetailerCollection', 'Session');

        $retailers->setCategoryId($this->currentCategoryId);
        $session->fetch($retailers);

        return $retailers->getParsedArray();
    }


    public function clearRetailers()
    {
        $retailers = $this->domainObjectFactory->create('RetailerCollection');
        $session = $this->dataMapperFactory->create('RetailerCollection', 'Session');
        $retailers->setCategoryId($this->currentCategoryId);

        $session->store($retailers);
    }

    private function acquireProduct()
    {
        $product = $this->domainObjectFactory->create('Product');
        $api = $this->dataMapperFactory->create('Product', 'REST');


        $product->setId($this->currentProductId);
        $api->fetch($product);

        return $product;
    }


    public function registerBuy()
    {
        if ($this->currentProduct === null ) {
            $this->currentProduct = $this->acquireProduct();
        }

        $product = $this->currentProduct;

        $db = $this->dataMapperFactory->create('Visitor', 'SQL');
        $db->store($this->currentUser);


        $visit = $this->acquireVisit($product);
        $visit->setVisitorId($this->currentUser->getVisitorId());


        $db = $this->dataMapperFactory->create('Redirect', 'SQL');
        $db->store($visit);


        $api = $this->dataMapperFactory->create('Visit', 'REST');
        $api->fetch($visit);

        $this->visit = $visit;
    }


    public function getVisitDetails()
    {
        if ($this->visit) {
            return ['link' => $this->visit->getTrackingLink()];
        }
        return null;
    }

    public function getRecommendations($count)
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setLimit($count);
        $products->setUserId($this->currentUser->getId());

        $db = $this->dataMapperFactory->create('RecommendationCollection', 'SQL');
        $db->fetch($products);


        $db = $this->dataMapperFactory->create('ProductCollection', 'SQL');
        $db->fetch($products);

        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');
        $api->fetch($products);

        $db->delete($products);

        return $products->getParsedArray();
    }

    public function toggleRetailer($id)
    {
        $retailers = $this->domainObjectFactory->create('RetailerCollection');
        $session = $this->dataMapperFactory->create('RetailerCollection', 'Session');

        $retailers->setCategoryId($this->currentCategoryId);
        $session->fetch($retailers);

        $retailers->toggleItem(['id' => $id]);

        $session->store($retailers);

    }

}