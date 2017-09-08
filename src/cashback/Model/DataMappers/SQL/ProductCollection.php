<?php

namespace Application\DataMappers\SQL;

class ProductCollection extends \Application\Common\SQLMapper
{
    public function delete($collection)
    {
        $todo = $collection->getRemovable();
        $keys = [];

        foreach ($todo['pool'] as $product) {
            $keys[$product->getId()] = 1;
        }

        $keys = implode(', ', array_keys($keys));

        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec("DELETE FROM VisitorRedirects WHERE productId IN ($keys)");
            $this->pdo->exec("DELETE FROM Wishes WHERE productId IN ($keys)");
            $this->pdo->exec("DELETE FROM VisitorViews WHERE productId IN ($keys)");
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
        }

        $collection->cleanup();
    }

    public function fetch($collection)
    {
        if ($collection->hasItems()) {
            $list = [];
            foreach ($collection as $product) {
                $list[(int)$product->getId()] = $product;
            }
            $keys = implode(', ', array_keys($list));

            $sql = "SELECT productId AS id FROM Wishes
                        LEFT JOIN Visitors USING (visitorId)
                    WHERE userId =:user AND productId IN ($keys)";

            try {
                $statement = $this->pdo->prepare($sql);
                $statement->bindValue(':user', $collection->getUserId(), \PDO::PARAM_STR);
                if ($statement->execute()) {
                    $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($data as $item) {
                        $list[$item['id']]->markAsFavorite();
                    }
                }

            } catch (\PDOException $e) {
                // I hope this wont be needed at any point
            }
        } else {
            $sql = "SELECT productID AS id FROM Wishes LEFT JOIN Visitors USING (visitorID) WHERE userId =:user";

            try {
                $statement = $this->pdo->prepare($sql);
                $statement->bindValue(':user', $collection->getUserId(), \PDO::PARAM_STR);
                if ($statement->execute()) {
                    $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($data as $entry) {
                        $collection->addItem($entry);
                    }
                }
            } catch (\PDOException $e) {
                // I hope this wont be needed at any point
            }
        }
    }
}
