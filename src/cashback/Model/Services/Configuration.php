<?php

namespace Application\Services;


class Configuration extends \Application\Common\Service
{

    private $data = [];
    private $codes = [];
    private $titles = [];
    private $contacts = null;


    public function import($config) {
        $this->importParameters('languages', $config['languages']);
        $this->importParameters('currencies', $config['currencies']);
        $this->importParameters('locations', $config['locations']);
        $this->contacts = $config['contacts'];
    }


    private function importParameters($key, $parameters) {
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



    public function getContacts()
    {
        return $this->contacts;
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


        $locations = array_keys($settings->getLocations());
        $temp = [];
        foreach ($this->data['locations'] as $value) {
            if (in_array($value['code'], $locations)) {
                $temp[] = $value['title'];
            }
        }



        return [
            'has-download-bar'   => $settings->getParam('no-download') != true,
            'selected-locations' => $locations,
            'location-list'      => $temp,
            'order-by'           => $settings->getOrder(),
            'currency'           => $settings->getCurrency(),
            'language'           => $settings->getLanguage(),
            'languages'          => $this->data['languages'],
            'locations'          => $this->data['locations'],
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


        if (in_array($param, $this->titles['locations'])) {
            $code = $this->findByTitle($param, 'locations');
            $settings->addLocation($code);
        }


        if (in_array($param, ['Newest added', 'Oldest added'])) {
            $settings->setOrder($param === 'Oldest added');
        }

        $session->store($settings);
    }



    public function delParam($param)
    {
        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $settings = $this->domainObjectFactory->create('Settings');

        $session->fetch($settings);



        if (in_array($param, $this->titles['locations'])) {
            $code = $this->findByTitle($param, 'locations');
            $settings->removeLocation($code);
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

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);


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

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);

        foreach ($this->data['languages'] as $key => $value) {
            if ($value['code'] === $settings->getLanguage()) {
                return $value;
            }
        }

        return $this->data['languages'][0];
    }


    public function setSearchState($id)
    {
        $settings = $this->domainObjectFactory->create('Settings');
        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);
        $settings->setSearchType($id);
        $session->store($settings);
    }

    public function getSearchState()
    {
        $settings = $this->domainObjectFactory->create('Settings');
        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);
        return $settings->getSearchType();
    }

    public function flushSearchState()
    {
        $this->setSearchState(null);
    }


    public function setCurrentCategory($id)
    {
        $settings = $this->domainObjectFactory->create('Settings');
        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $session->fetch($settings);
        $settings->setLastCategory($id);
        $session->store($settings);
    }



}