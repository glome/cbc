<?php

    namespace Application\DataMappers\Cache;

    class CategoryCollection extends \Application\Common\CacheMapper
    {

        public function fetch($collection)
        {
            $data = $this->cache->get('glome.categories');
            if ($data) {
                $collection->import(json_decode($data, true));
                return true;
            }
            return false;
        }

        public function store($collection)
        {
            $this->cache->set('glome.categories', json_encode($collection->export()), time() + 3600);
            $data = $this->cache->get('glome.categories');
        }
    }