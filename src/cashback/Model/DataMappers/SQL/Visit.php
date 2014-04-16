<?php

    namespace Application\DataMappers\SQL;

    class Visit extends \Application\Common\SQLMapper
    {

        public function fetch($instance)
        {

        }


        public function store($instance)
        {
            $statement = $this->pdo->prepare('INSERT INTO VisitorViews(visitorId, productId, categoryId) VALUES (:visitor, :product, :category)');
            try {
                $statement->bindValue(':visitor', $instance->getVisitorId(), \PDO::PARAM_INT);
                $statement->bindValue(':product', $instance->getProductId(), \PDO::PARAM_INT);
                $statement->bindValue(':category', $instance->getCategoryId(), \PDO::PARAM_INT);
                $statement->execute();
            } catch (\PDOException $e) {
                $instance->setError($e->getCode(), $e->getMessage());
            }
        }


        public function delete($instance)
        {

        }

    }