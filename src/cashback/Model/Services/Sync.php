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
            $sync->setKind('s');
            $sync->setUserId($this->currentUser->getId());
            $sync->setSessionId(session_id());
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
            $sync->setKind('b');
            $sync->setUserId($this->currentUser->getId());
        }

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->fetchBrothers($sync);

        return $sync->getBrothers();
    }

    public function postUnPair($syncId)
    {
        $sync = $this->domainObjectFactory->create('Sync');
        if ($this->currentUser) {
            $sync->setKind('s');
            $sync->setUserId($this->currentUser->getId());
        }
        $sync->setId($syncId);

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->togglePairing($sync);
    }

    public function getUnlockCode()
    {
        $sync = $this->domainObjectFactory->create('Sync');

        if ($this->currentUser) {
            if ($this->currentUser->getUnlockCode()) {
                exit;
            }
            $sync->setKind('u');
            $sync->setUserId($this->currentUser->getId());
            $sync->setSessionId(session_id());
        }

        $api = $this->dataMapperFactory->create('Sync', 'REST');
        $api->fetch($sync);

        $this->currentUser->setUnlockCode($sync->getPairingCode());

        return $sync->getPairingCode();
    }
}
