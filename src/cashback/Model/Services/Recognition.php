<?php

namespace Application\Services;


class Recognition extends \Application\Common\Service
{

    private $current = null;


    public function authenticate()
    {
        $user = $this->domainObjectFactory->create('User');

        $cookies = $this->dataMapperFactory->create('User', 'Cookie');
        $session = $this->dataMapperFactory->create('User', 'Session');

        if (!$cookies->fetch($user) || !$session->fetch($user)) {
            $api = $this->dataMapperFactory->create('User', 'REST');
            $api->fetch($user);
            $session->store($user);
        }

        $cookies->store($user);
        $this->current = $user;
    }
}