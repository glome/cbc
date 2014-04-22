<?php

namespace Application\Services;


class Library extends \Application\Common\Service
{

    public function readDocument($name)
    {
        $content = $this->domainObjectFactory->create('Content');
        $http = $this->dataMapperFactory->create('Content', 'HTTP');

        $content->setName($name);
        $http->fetch($content);

        return $content->getOutput();

    }

}