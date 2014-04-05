<?php

    namespace Application\DomainObjects;

    class Settings
    {


        private $persistent = [];

        public function setParam($name, $value)
        {
            $this->persistent[$name] = $value;
        }

        public function getParams()
        {
            return $this->persistent;
        }

        public function getParam($name)
        {
            if (!isset($this->persistent[$name])) {
                return null;
            }

            return $this->persistent[$name];
        }

    }