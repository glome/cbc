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



    public function setPage($page)
    {
        $this->currentPage = $page;
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
        $api = $this->dataMapperFactory->create('CategoryCollection', 'REST');
        $api->fetch($categories);

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
        $api->fetch($category);

        return $category->getParentId();

    }


    public function getProducts()
    {


        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setCategory($this->currentCategoryId);


        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->fetch($products);


        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');
        $products->setPage($this->currentPage);
        $api->fetch($products);


        $db = $this->dataMapperFactory->create('ProductCollection', 'SQL');
        $products->setUserId($this->currentUser->getId());
        $db->fetch($products);




        return $products->getParsedArray();
    }


    public function prepareSearch($query)
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $products->setQuery($query);
        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->store($products);
    }


    public function getSearchedTerm()
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $session = $this->dataMapperFactory->create('ProductCollection', 'Session');
        $session->fetch($products);

        $term = $products->getQuery();
        return $term !== null ? $term : '';
    }



    public function setCurrentProduct($productId)
    {
        $this->currentProductId = $productId;
    }

    public function getCurrentProduct()
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

        $visit = $this->domainObjectFactory->create('Visit');
        $visit->setProductId($product->getId());
        $visit->setCategoryId($product->getCategoryId());

        $session = $this->dataMapperFactory->create('Visit', 'Session');
        if (!$session->fetch($visit)) {
            $db = $this->dataMapperFactory->create('Visitor', 'SQL');
            $db->store($this->currentUser);
            $visit->setVisitorId($this->currentUser->getVisitorId());
            $db = $this->dataMapperFactory->create('Visit', 'SQL');
            $db->store($visit);
            $session->store($visit);
        }

        return $product->getParsedArray();
    }


    private function acquireProduct()
    {
        $product = $this->domainObjectFactory->create('Product');
        $api = $this->dataMapperFactory->create('Product', 'REST');

        $product->setId($this->currentProductId);
        $api->fetch($product);

        return $product;
    }


    public function registerRedirect()
    {
        if ($this->currentProduct === null ) {
            $this->currentProduct = $this->acquireProduct();
        }

        $product = $this->currentProduct;

        $db = $this->dataMapperFactory->create('Visitor', 'SQL');
        $db->store($this->currentUser);

        $redirect = $this->domainObjectFactory->create('Visit');
        $redirect->setProductId($product->getId());
        $redirect->setCategoryId($product->getCategoryId());
        $redirect->setVisitorId($this->currentUser->getVisitorId());

        $db = $this->dataMapperFactory->create('Redirect', 'SQL');
        $db->store($redirect);

    }


}