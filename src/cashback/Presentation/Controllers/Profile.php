<?php

namespace Application\Controllers;

class Profile extends \Application\Common\Controller
{
    public function getIndex($request, $check = true)
    {
        if ($check) {
            $this->checkLocked();
        }

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $sync = $this->serviceFactory->create('Sync');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);
        $sync->forUser($this->user);
    }

    public function getWishlist($request)
    {
        $this->getIndex($request);
    }

    public function getHistory($request)
    {
        $this->getIndex($request);
    }

    public function getWish($request)
    {
        $this->checkLocked();
        $itinerary = $this->serviceFactory->create('Itinerary');

        $itinerary->forUser($this->user);
        $itinerary->addWish($request->getParameter('id'));
    }

    public function getSpurn($request)
    {
        $this->checkLocked();

        $itinerary = $this->serviceFactory->create('Itinerary');

        $itinerary->forUser($this->user);
        $itinerary->removeWish($request->getParameter('id'));
    }

    public function getPairing($request)
    {
        $code = $request->getParameter('code');
        if ($code && strlen($code) == 12) {
            $syncService = $this->serviceFactory->create('Sync');
            $syncService->forUser($this->user);
            $code_arr = str_split($code, 4);
            $syncService->postPairingCode($code_arr[0], $code_arr[1], $code_arr[2]);
        }
        $this->getIndex($request);
    }

    public function postPairing($request)
    {
        $this->checkLocked();

        $sync = $this->serviceFactory->create('Sync');
        $sync->forUser($this->user);

        if ($request->getParameter('unpair')) {
          $sync->postUnPair($request->getParameter('sync_id'));
        } else {
          $sync->postPairingCode($request->getParameter('code_1'), $request->getParameter('code_2'), $request->getParameter('code_3'));
        }

        $this->getIndex($request);
    }

    public function getLocking($request)
    {
        $this->getIndex($request);
    }

    public function postLocking($request)
    {
        $this->checkLocked();
        $this->recognition->postLocking();
        $this->getIndex($request);
    }

    public function getUnlocking($request)
    {
        if ($this->user->isLocked()) {
            $this->getIndex($request, false);
        } else {
          header('Location: /profile/');
          exit;
        }
    }

    public function getQR($request)
    {
    }
}
