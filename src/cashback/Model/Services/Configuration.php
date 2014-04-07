<?php

namespace Application\Services;


class Configuration extends \Application\Common\Service
{
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
            'currency'         => $settings->getCurrency(),
        ];

    }


    public function applyParam($param) {

        $session = $this->dataMapperFactory->create('Settings', 'Session');
        $settings = $this->domainObjectFactory->create('Settings');

        if (in_array($param, ['EUR', 'USD', 'GBP'])) {
            $settings->setCurrency($param);
        }

        if (in_array($param, ['Deutsch', 'English', 'Suomi'])) {
            $settings->setLanguage($param);
        }

        if (in_array($param, ['Newest added', 'Oldest added'])) {
            $settings->setOrder($param === 'Oldest added');
        }

        $session->store($settings);

    }


    public function getPreferredCurrency()
    {
        return [
            'name' => 'EUR',
            'symbol' => '€',
        ];
    }

}