<?php

    namespace Application\DataMappers\SQL;

    class Wish extends \Application\Common\SQLMapper
    {

        public function fetch($instance)
        {

        }


        public function store($instance)
        {
            $statement = $this->pdo->prepare('INSERT INTO Wishes(visitorId, productID)
                                              VALUES (:visitor, :product)');
            try {
                $statement->bindValue(':visitor', $instance->getVisitorId(), \PDO::PARAM_INT);
                $statement->bindValue(':product', $instance->getProductId(), \PDO::PARAM_INT);
                $statement->execute();
            } catch (\PDOException $e) {
                $instance->setError($e->getCode(), $e->getMessage());
            }
        }


        public function delete($instance)
        {

        }

    }