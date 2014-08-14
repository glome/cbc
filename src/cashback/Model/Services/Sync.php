<?php

namespace Application\Services;

class Sync extends \Application\Common\Service
{
    private $sync = null;
    private $currentUser;

    public function forUser($user)
    {
        $this->currentUser = $user;
    }

    public function getUser()
    {
        return $this->currentUser;
    }

    public function getPairingCode()
    {
        $sync = $this->domainObjectFactory->create('Sync');

        if ($this->currentUser) {
            $sync->setUserId($this->currentUser->getId());
        }

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->fetch($sync);

        return $sync->getPairingCode();
    }

    public function postPairingCode($code_1, $code_2, $code_3)
    {
        $this->sync = $this->domainObjectFactory->create('Sync');

        $this->sync->setCode1($code_1);
        $this->sync->setCode2($code_2);
        $this->sync->setCode3($code_3);

        $this->sync->setUserId($this->currentUser->getId());

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->store($this->sync);
    }

    public function getSync()
    {
        return $this->sync;
    }

    /**
     * Get a list of all synced CBC instances
     */
    public function getBrothers()
    {
        $sync = $this->domainObjectFactory->create('Sync');
        if ($this->currentUser) {
            $sync->setUserId($this->currentUser->getId());
        }

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->fetchBrothers($sync);

        return $sync->getBrothers();
    }

    public function postUnPair($syncId)
    {
        echo "unpair: " . $syncId . "\n<br/>";

        $sync = $this->domainObjectFactory->create('Sync');
        if ($this->currentUser) {
            $sync->setUserId($this->currentUser->getId());
        }
        $sync->setId($syncId);

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->togglePairing($sync);
    }
}
