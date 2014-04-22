<?php

namespace Application\Views;

class Article extends \Application\Common\View
{
    public function index()
    {
        $library = $this->serviceFactory->create('Library');
        $document = $library->readDocument('faq');
        return $document;
    }
}
