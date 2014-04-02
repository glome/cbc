<?php

namespace Application\DomainObjects;

class CategoryCollection extends \Application\Common\Collection
{

    protected function buildItem()
    {
        return new \Application\DomainObjects\Category;
    }



    public function getParsedArray()
    {

        foreach ($this->pool as $category) {
            $id = $category->getId();
            $data[$id] = ['name' => $category->getName()];
            foreach ($category->getSubcategories() as $subcategory) {
                $data[$id]['subcategories'][$subcategory->getId()] = ['name' => $subcategory->getName()];
            }
        }

        return $data;
    }

}