<?php

namespace Application\Services;


class Shop extends \Application\Common\Service
{

    private $currentCategoryId = null;
    private $currentProductId = null;
    private $categoryTree = null;
    private $currentUser = null;


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
        $product = $this->domainObjectFactory->create('Product');
        $api = $this->dataMapperFactory->create('Product', 'REST');

        $product->setId($this->currentProductId);
        $api->fetch($product);


        $db = $this->dataMapperFactory->create('Wish', 'SQL');
        $wish = $this->domainObjectFactory->create('Wish');
        $wish->setUserId($this->currentUser->getId());
        $wish->setProductId($product->getId());

        if ($db->fetch($wish))
        {
            $product->markAsFavorite();
        }
        return $product->getParsedArray();
    }



}