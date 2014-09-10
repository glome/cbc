<?php

namespace Application\Services;

class Recognition extends \Application\Common\Service
{
    private $currentUser = null;

    public function forUser($user)
    {
        $this->currentUser = $user;
    }

    public function getUser()
    {
        return $this->currentUser;
    }

    public function authenticate($force = false)
    {
        $user = $this->domainObjectFactory->create('User');

        $cookies = $this->dataMapperFactory->create('User', 'Cookie');
        $session = $this->dataMapperFactory->create('User', 'Session');

        if (!$cookies->fetch($user) || !$session->fetch($user) || $force) {
            $api = $this->dataMapperFactory->create('User', 'REST');
            $api->fetch($user);
            $session->store($user);
        }

        $cookies->store($user);
        $this->forUser($user);
    }

    public function postLocking()
    {
        $user = $this->getUser();

        if ($user) {
            $api = $this->dataMapperFactory->create('User', 'REST');
            $user->setLockedAt(null);
            $api->update($user);
        }
        //$cookies->store($this->user);
        //$this->forUser($this->user);
    }
}
