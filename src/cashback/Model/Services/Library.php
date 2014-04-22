<?php

namespace Application\Services;


class Library extends \Application\Common\Service
{

    public function readDocument($name)
    {
        //$settings = $this->domainObjectFactory->create('Content');
        //$session = $this->dataMapperFactory->create('Content', 'HTTP');

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile("http://stage.cashbackcatalog.com/cms/en/faq");
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        $content = $xpath->query("//*[@id='content']/*/*[@class='entry-content']")->item(0);


        $output = new \DOMDocument();
        $output->appendChild($output->importNode($content, true));
        return $output->saveHTML();
    }

}