<?php

namespace Application\DataMappers\SQL;

class Redirect extends \Application\Common\SQLMapper
{

    public function fetch($instance)
    {

    }


    public function store($instance)
    {
        $sql = 'INSERT INTO VisitorRedirects(visitorId, productId, categoryId)
                VALUES (:visitor, :product, :category)';

        $statement = $this->pdo->prepare($sql);
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
