<?php

    namespace Application\DataMappers\SQL;

    class Wishlist extends \Application\Common\SQLMapper
    {

        public function fetch($instance)
        {
        }


        public function store($instance)
        {
        }


        public function delete($instance)
        {
            unset($_SESSION['glome.query']);
        }

    }