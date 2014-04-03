<?php

namespace Application\Services;


class Shop extends \Application\Common\Service
{

    private $currentCategoryId = null;
    private $currentProductId = null;

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

        $categories = $this->domainObjectFactory->create('CategoryCollection');
        $cache = $this->dataMapperFactory->create('CategoryCollection', 'Cache');

        if (!$cache->fetch($categories)) {
            $api = $this->dataMapperFactory->create('CategoryCollection', 'REST');
            $api->fetch($categories);
            foreach ($categories as $category) {
                $api->fetch($category);
            }
            $cache->store($categories);
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

        return $product->getParsedArray();
    }



}