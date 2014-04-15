<?php

namespace Application\DomainObjects;

class ProductCollection extends \Application\Common\Collection
{

    private $categoryId = null;
    private $query = null;
    private $userId = null;
    private $page = null;


    private $langauge = null;
    private $currency = null;
    private $order = false;

    private $limit = null;

    private $advertisers = [];


    private $locations = [];



    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    public function getLocationQuery()
    {
        $locations = $this->locations;
        if (count($locations)) {
            return implode(',', $locations);
        }
        return 'any';
    }

    public function setAdvertisers($advertisers)
    {
        $this->advertisers = $advertisers;
    }

    public function getAdvertisers()
    {
        if (count($this->advertisers)) {
            return $this->advertisers[0];
        }

        return '';
    }


    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }



    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }



    public function setUserID($id)
    {
        $this->userId = $id;
    }


    public function setPage($page)
    {
        $this->page = $page;
    }


    public function getPage()
    {
        $page = (int)$this->page;
        if ($page === 0)
        {
            $page = 1;
        }
        return $page;
    }

    public function getUserId()
    {
        return $this->userId;
    }


    protected function buildItem()
    {
        return new Product;
    }

    public function setCategory($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function hasCategory()
    {
        return $this->categoryId !== null;
    }

    public function getCategory()
    {
        return $this->categoryId;
    }

    public function setQuery($query)
    {
        $query = trim($query);
        if (!empty($query)) {
            $this->query = $query;
        }
    }

    public function hasQuery()
    {
        return $this->query !== null;
    }


    public function getQuery()
    {
        return $this->query;
    }



    public function getParsedArray()
    {
        $data = [];
        foreach ($this as $product) {

            $id = $product->getId();
            $data[$id] = $product->getParsedArray();
        }

        return $data;
    }


    public function applyIncentives($incentives)
    {
        $cache = [];
        foreach ($this as $product) {
            $id = $product->getIncentiveId();

            if ($id === false) {
                continue;
            }

            if (!array_key_exists($id, $cache)) {
                $cache[$id] = $incentives->locateIncentive($id);
            }

            $product->applyIncentive($cache[$id]);
        }
    }




}