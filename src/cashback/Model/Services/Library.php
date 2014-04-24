<?php

namespace Application\Services;


class Library extends \Application\Common\Service
{

    public function readDocument($name)
    {
        $settings = $this->domainObjectFactory->create('Settings');

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);

        $content = $this->domainObjectFactory->create('Content');
        $http = $this->dataMapperFactory->create('Content', 'HTTP');

        $content->setLanguage($settings->getLanguage());
        $content->setName($name);
        $http->fetch($content);

        return $content->getOutput();

    }
}
