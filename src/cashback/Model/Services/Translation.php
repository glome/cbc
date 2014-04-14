<?php

namespace Application\Services;


class Translation extends \Application\Common\Service
{

    private $lang = 'en';
    private $dir = null;
    private $cache = null;

    public function useLanguage($lang = 'en')
    {
        $this->lang = strtolower($lang);
    }


    public function setTranslationRoot($dir)
    {
        $this->dir = $dir;
    }

    public function checkSettings()
    {
        $settings = $this->domainObjectFactory->create('Settings');

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);

        $this->useLanguage($settings->getLanguage());
    }


    public function fetchTranslations()
    {
        if ($this->cache === null ) {
            $filename = "{$this->dir}/{$this->lang}.json";
            if (file_exists($filename)) {
                $this->cache = json_decode(file_get_contents($filename), true);
            } else {
                $this->cache = [];
            }
        }
        return $this->cache;
    }


}