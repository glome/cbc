<?php

    namespace Application\DataMappers\Session;

    class Category extends \Application\Common\SessionMapper
    {

        public function fetch($instance)
        {
            return false;

            if (isset($_SESSION['glome.categories'])) {
                $categories = $_SESSION['glome.categories'];

                foreach ($categories as $category) {
                    $i = $category['id'];
                    foreach ($category['subcategories'] as $subcategory) {
                        if ($subcategory['id'] === (int)$instance->getId())
                        {
                            $instance->setLinkedTo($i);
                            $instance->setName($subcategory['name']);
                            return true;
                        }
                    }
                }
            }
            return false;
        }


        public function store($instance)
        {
            $_SESSION['glome.categories'] = $instance->export();
        }


        public function delete($instance)
        {
            unset($_SESSION['glome.query']);
        }

    }