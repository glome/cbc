<?php

namespace Application\DataMappers\HTTP;

class Content extends \Application\Common\HTTPMapper
{
    public function fetch($instance)
    {

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);

        $language = strtolower($instance->getLanguage());

        if (isset($this->config[$language][$instance->getName()])) {
            $name = $instance->getName();
            $path = $this->config[$language][$name]['url'];
            $worked = @$doc->loadHTMLFile($path);
            libxml_clear_errors();

            if (!$worked) {
                return;
            }

            $xpath = new \DOMXPath($doc);
            $content = $xpath->query($this->config[$language][$name]['xpath'])->item(0);

            $output = new \DOMDocument();
            $output->appendChild($output->importNode($content, true));

            $output = $output->saveHTML();
            $instance->setOutput($output);
        }

    }
}
