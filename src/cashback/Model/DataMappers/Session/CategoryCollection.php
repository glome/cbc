<?php

namespace Application\DataMappers\Session;

class CategoryCollection extends \Application\Common\SessionMapper
{

    public function fetch($collection)
    {
        return false; // disables caching

        if (isset($_SESSION['glome.categories'])) {
            $collection->import($_SESSION['glome.categories']);
            return true;
        }
        return false;
    }


    public function store($collection)
    {
        $_SESSION['glome.categories'] = $collection->export();
    }


    public function delete($collection)
    {
        unset($_SESSION['glome.query']);
    }
}
