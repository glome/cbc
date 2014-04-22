<?php

    namespace Application\DataMappers\HTTP;

    class Content extends \Application\Common\HTTPMapper
    {
        public function fetch($instance)
        {

            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);

            if (isset($this->config['en'][$instance->getName()])) {
                $path = $this->config['en'][$instance->getName()]['url'];
                $doc->loadHTMLFile($path);
                libxml_clear_errors();

                $xpath = new \DOMXPath($doc);
                $content = $xpath->query( $this->config['en'][$instance->getName()]['xpath'])->item(0);

                $output = new \DOMDocument();
                $output->appendChild($output->importNode($content, true));

                $output = $output->saveHTML();
                $instance->setOutput($output);
            }

        }

    }