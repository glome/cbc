<?php

namespace Application\Common;

class Controller
{
    protected $serviceFactory;
    protected $recognition;
    protected $user;

    public function __construct($serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->recognition = $this->serviceFactory->create('Recognition');
        $this->user = $this->recognition->getUser();
        if (get_class($this) != "Application\Controllers\Profile") {
            $this->checkLocked();
        }
    }

    public function checkLocked()
    {
        $error = $this->user->getErrorCode();
        # TODO: get error codes from Glome
        if (isset($error) && $error == 2301) {
            header('Location: /profile/unlocking');
            exit;
            return;
        }
    }
}
