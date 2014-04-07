<?php

    namespace Application\DataMappers\SQL;

    class Wish extends \Application\Common\SQLMapper
    {

        public function fetch($instance)
        {
            $statement = $this->pdo->prepare('SELECT 1 FROM Wishes LEFT JOIN Visitors USING (visitorId) WHERE userId = :user AND productID = :product');
            try {
                $statement->bindValue(':user', $instance->getUserId(), \PDO::PARAM_INT);
                $statement->bindValue(':product', $instance->getProductId(), \PDO::PARAM_INT);
                $statement->execute();
                $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
                if (count($data)) {
                    return true;
                }
            } catch (\PDOException $e) {
                $instance->setError($e->getCode(), $e->getMessage());
            }

            return false;

        }


        public function store($instance)
        {
            $statement = $this->pdo->prepare('INSERT INTO Wishes(visitorId, productID, categoryId)
                                              VALUES (:visitor, :product, :category)');
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
            $statement = $this->pdo->prepare('DELETE FROM Wishes WHERE visitorId = :visitor AND productID = :product');
            try {
                $statement->bindValue(':visitor', $instance->getVisitorId(), \PDO::PARAM_INT);
                $statement->bindValue(':product', $instance->getProductId(), \PDO::PARAM_INT);
                $statement->execute();
            } catch (\PDOException $e) {
                $instance->setError($e->getCode(), $e->getMessage());
            }
        }

    }