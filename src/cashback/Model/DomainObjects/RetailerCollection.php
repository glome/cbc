<?php

namespace Application\DomainObjects;

class RetailerCollection extends \Application\Common\Collection
{
    private $categoryId = null;
    private $page = null;
    private $locations = [];

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        $page = (int)$this->page;
        if ($page === 0) {
            $page = 1;
        }
        return $page;
    }

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

    protected function buildItem()
    {
        return new Retailer;
    }

    public function setCategoryId($id)
    {
        $this->categoryId = $id;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getParsedArray()
    {
        $list = [];
        foreach ($this as $item) {
            $list[$item->getId()] = $item->getParsedArray();
        }
        return $list;
    }

    public function toggleItem($params)
    {
        $removed = false;

        foreach ($this as $key => $item) {
            if ($item->getId() === $params['id']) {
                $this->removeItem($key);
                $removed = true;
            }
        }

        if ($removed) {
            $this->cleanup();
        } else {
            $this->addItem($params);
        }
        return;
    }

    public function getRemovableIdList()
    {
        $list = [];
        foreach ($this->forRemoval as $key => $item) {
            $list[] = $item->getId();
        }

        return $list;
    }
}
