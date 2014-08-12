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

    public function getCurrentUser()
    {
        return $this->current;
    }

    public function getPairingCode()
    {
        if ($this->current === null) {
            return;
        }

        $user = $this->current;
        $session = $this->dataMapperFactory->create('User', 'Session');
        $api = $this->dataMapperFactory->create('Sync', 'REST');

        if ($api->fetch($user)) {
            $session->store($user);
        }

        return $user->getPairingCode();
    }

    public function postPairingCode($code_1, $code_2, $code_3)
    {
        $sync = $this->domainObjectFactory->create('Sync');

        $sync->setCode1($code_1);
        $sync->setCode2($code_2);
        $sync->setCode3($code_3);

        $sync->setUserId($this->current->getId());

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->store($sync);

        echo "posted: ";
        var_dump($sync);
        echo "<br/>\n----------";
    }

}
