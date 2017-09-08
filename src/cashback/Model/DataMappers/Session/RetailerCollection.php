<?php

namespace Application\DataMappers\Session;

class RetailerCollection extends \Application\Common\SessionMapper
{
    public function fetch($collection)
    {
        $session = isset($_SESSION['retailers']) ? $_SESSION['retailers'] : [];
        $category = $collection->getCategoryId();
        $list = isset($session[$category]) ? $session[$category] : [];

        foreach ($list as $id => $junk) {
            $id = str_replace('id-', '', $id);
            $collection->addItem(['id' => $id]);
        }

        return true;
    }

    public function store($collection)
    {
        $category = $collection->getCategoryId();
        $list = [];

        foreach ($collection as $item) {
            $list['id-'.$item->getId()] = 1;
        }

        $_SESSION['retailers'][$category] = $list;
    }

    public function delete($collection)
    {
        $category = $collection->getCategoryId();
        $list = $collection->getRemovableIdList();

        foreach ($list as $key) {
            unset($_SESSION['retailers'][$category][$key]);
        }
    }
}
