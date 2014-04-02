<?php

namespace Application\Services;


class Shop extends \Application\Common\Service
{

    private $id = null;

    public function getCategories()
    {

        $categories = $this->domainObjectFactory->create('CategoryCollection');
        $cache = $this->dataMapperFactory->create('CategoryCollection', 'Cache');

        if (!$cache->fetch($categories)) {
            $api = $this->dataMapperFactory->create('CategoryCollection', 'REST');
            $api->fetch($categories);
            $cache->store($categories);
        }

        return $categories->getParsedArray();

    }



    public function useCategory($id)
    {

    }


    public function getProducts()
    {
        $products = $this->domainObjectFactory->create('ProductCollection');
        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');

        $api->fetch($products);
    }



}