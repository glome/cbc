<?php

namespace Application\DomainObjects;

class CategoryCollection extends \Application\Common\Collection
{

    protected function buildItem()
    {
        return new \Application\DomainObjects\CategoryCollection;
    }

    private $name = null;
    private $id = null;
    private $subcategories = [];
    private $parentId = null;

    public function setName($name)
    {
        $this->name =$name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
    }


    public function setLinkedTo($id)
    {
        $this->parentId = $id;
    }

    public function getParentId()
    {
        return $this->parentId;
    }


    public function getParsedArray()
    {
        $data = [];
        foreach ($this as $category) {

            $id = $category->getId();
            $data[$id] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'subcategories' => $category->getParsedArray(),
            ];
        }

        return $data;
    }


    public function export()
    {
        $data = [];
        foreach ($this as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'subcategories' => $category->export(),
            ];
        }
        if (count($data) === 0) {
            $data = null;
        }
        return $data;
    }


    public function import($data)
    {
        if ($data === null) {
            return;
        }
        foreach ($data as  $params) {
            $collection = $this->addItem($params);;
            $collection->import($params['subcategories']);
        }
    }

}