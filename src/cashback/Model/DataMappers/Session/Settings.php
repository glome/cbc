<?php

    namespace Application\DataMappers\Session;

    class Settings extends \Application\Common\SessionMapper
    {

        public function fetch($instance)
        {
            if (isset($_SESSION['config.language'])) {
                $instance->setLanguage($_SESSION['config.language']);
            }

            if (isset($_SESSION['config.currency'])) {
                $instance->setCurrency($_SESSION['config.currency']);
            }

            if (isset($_SESSION['config.locations'])) {
                $instance->setLocations($_SESSION['config.locations']);
            }


            if (isset($_SESSION['config.order'])) {
                $instance->setOrder($_SESSION['config.order']);
            }


            return true;
        }


        public function store($instance)
        {
            $_SESSION['config.language'] = $instance->getLanguage();
            $_SESSION['config.currency'] = $instance->getCurrency();
            $_SESSION['config.locations'] = $instance->getLocations();
            $_SESSION['config.order'] = $instance->getOrder();
        }

    }