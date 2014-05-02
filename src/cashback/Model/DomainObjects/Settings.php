<?php

namespace Application\DomainObjects;

class Settings
{
    private $persistent = [];
    private $language = 'EN';
    private $currency = 'EUR';
    private $order = false;
    private $searchType = null;
    private $lastCategory = null;
    private $locations = [];

    public function setLastCategory($lastCategory)
    {
        $this->lastCategory = $lastCategory;
    }

    public function getLastCategory()
    {
        return $this->lastCategory;
    }

    public function setSearchType($id)
    {
        $this->searchType = $id === null ? null : (int)$id;
    }

    public function getSearchType()
    {
        return $this->searchType;
    }

    public function setParam($name, $value)
    {
        $this->persistent[$name] = $value;
    }

    public function getParams()
    {
        return $this->persistent;
    }

    public function getParam($name)
    {
        if (!isset($this->persistent[$name])) {
            return null;
        }

        return $this->persistent[$name];
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
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

    public function getLocations()
    {
        return $this->locations;
    }

    public function addLocation($location)
    {
        $this->locations[$location] = 1;
    }

    public function removeLocation($location)
    {
        unset($this->locations[$location]);
    }

    public function setLocations($list)
    {
        $this->locations = $list;
    }
}
