<?php

namespace Application\Services;


class Shop extends \Application\Common\Service
{
    public function getCategories()
    {

        $categories = $this->domainObjectFactory->create('CategoryCollection');
        $cacheMapper = $this->dataMapperFactory->create('CategoryCollection', 'Cache');

        if (!$cacheMapper->fetch($categories)) {
            $remoteMapper = $this->dataMapperFactory->create('CategoryCollection', 'REST');
            $remoteMapper->fetch($categories);
            $cacheMapper->store($categories);
        }

        return $categories->getParsedArray();

    }
}