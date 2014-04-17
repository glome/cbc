<?php

    namespace Application\DataMappers\Session;

    class ProductCollection extends \Application\Common\SessionMapper
    {

        public function fetch($instance)
        {
            if (!isset($_SESSION['glome.query'])) {
                return false;
            }

            $instance->setQuery($_SESSION['glome.query']);
            return true;
        }


        public function store($instance)
        {
            $value = $instance->getQuery();
            if ($value !== '') {
                $_SESSION['glome.query'] = $value;
            }
        }


        public function delete($instance)
        {
            unset($_SESSION['glome.query']);
        }

    }