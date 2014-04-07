<?php

    namespace Application\DataMappers\Session;

    class Visit extends \Application\Common\SessionMapper
    {

        public function fetch($instance)
        {
            if (!isset($_SESSION['glome.visits'])) {
                return false;
            }

            return array_key_exists($instance->getProductId(), $_SESSION['glome.visits']);
        }


        public function store($instance)
        {
            $_SESSION['glome.visits'][$instance->getProductId()] = 1;
        }

    }