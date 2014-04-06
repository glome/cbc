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

        return [
            'has-download-bar' => $settings->getParam('no-download') != true,
        ];

    }


    public function getPreferredCurrency()
    {
        return [
            'name' => 'EUR',
            'symbol' => '€',
        ];
    }

}