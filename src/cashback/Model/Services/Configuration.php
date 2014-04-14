<?php

namespace Application\Services;


class Configuration extends \Application\Common\Service
{

    private $data = [];
    private $codes = [];
    private $titles = [];


    public function import($config) {
        $this->inportParameters('languages', $config['languages']);
        $this->inportParameters('currencies', $config['currencies']);
    }


    private function inportParameters($key, $parameters) {
        $this->data[$key] = [];
        foreach ($parameters as $item) {
            if (isset($item['code'])) {
                $this->codes[$key][] = $item['code'];
            }

            if (isset($item['title'])) {
                $this->titles[$key][] = $item['title'];
            }

            if (isset($item['default']) && $item['default'] === true) {
                unset($item['default']);
                array_unshift($this->data[$key], $item);
                continue;
            }
            $this->data[$key][] = $item;
        }
    }


    public function getListOf($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return false;
    }



    public function disableDownlaodBar()
    {
        $settings = $this->domainObjectFactory->create('Settings');
        $settings->setParam('no-download', true);
        $cookies = $this->dataMapperFactory->create('Settings', 'Cookie');

        $cookies->store($settings);
    }

    public function getCurrentSettings()
    {
        $settings = $this->domainObjectFactory->create('Settings');

        $cookies = $this->dataMapperFactory->create('Settings', 'Cookie');
        $cookies->fetch($settings);

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);


        return [
            'has-download-bar' => $settings->getParam('no-download') != true,
            'order-by'         => $settings->getOrder(),
            'language'         => $settings->getLanguage(),
            'languages'        => $this->data['languages'],
            'currency'         => $settings->getCurrency(),
        ];

    }


    public function applyParam($param) {

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $settings = $this->domainObjectFactory->create('Settings');

        $session->fetch($settings);

        if (in_array($param, $this->codes['currencies'])) {
            $settings->setCurrency($param);
        }

        if (in_array($param, $this->titles['languages'])) {
            $code = $this->findByTitle($param, 'languages');
            $settings->setLanguage($code);
        }

        if (in_array($param, ['Newest added', 'Oldest added'])) {
            $settings->setOrder($param === 'Oldest added');
        }

        $session->store($settings);
    }

    private function findByTitle($title, $key)
    {
        foreach ($this->data[$key] as $item) {
            if ($item['title'] === $title) {
                return $item['code'];
            }
        }

        return $item[0]['code'];
    }


    public function getPreferredCurrency()
    {
        $settings = $this->domainObjectFactory->create('Settings');

        $cookies = $this->dataMapperFactory->create('Settings', 'Session');
        $cookies->fetch($settings);


        foreach ($this->data['currencies'] as $key => $value) {
            if ($value['code'] === $settings->getCurrency()) {
                return $value;
            }
        }

        return $this->data['currencies'][0];
    }


    public function getPreferredLanguage()
    {
        $settings = $this->domainObjectFactory->create('Settings');

        $cookies = $this->dataMapperFactory->create('Settings', 'Session');
        $cookies->fetch($settings);

        foreach ($this->data['languages'] as $key => $value) {
            if ($value['code'] === $settings->getLanguage()) {
                return $value;
            }
        }

        return $this->data['languages'][0];
    }


}