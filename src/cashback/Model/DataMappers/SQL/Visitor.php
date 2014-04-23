<?php

namespace Application\DataMappers\SQL;

class Visitor extends \Application\Common\SQLMapper
{

    public function fetch($instance)
    {

    }


    public function store($instance)
    {
        $sql = 'INSERT INTO Visitors(userId) VALUES (:user)
                ON DUPLICATE KEY UPDATE visitorID = LAST_INSERT_ID(visitorID)';

        $statement = $this->pdo->prepare($sql);
        try {
            $statement->bindValue(':user', $instance->getId(), \PDO::PARAM_STR);
            if ($statement->execute()) {
                $id = $this->pdo->lastInsertId();
                $instance->setVisitorId($id);
            }
        } catch (\PDOException $e) {
            $instance->setError($e->getCode(), $e->getMessage());
        }
    }


    public function delete($instance)
    {

    }
}
