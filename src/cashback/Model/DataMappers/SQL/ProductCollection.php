<?php

    namespace Application\DataMappers\SQL;

    class ProductCollection extends \Application\Common\SQLMapper
    {

        public function fetch($collection)
        {


            if ($collection->hasItems()) {

                $list = [];
                foreach ($collection as $product) {
                    $list[(int)$product->getId()] = $product;
                }
                $keys = implode(', ', array_keys($list));

                $sql = "SELECT productID AS id FROM Wishes LEFT JOIN Visitors USING (visitorID) WHERE userId =:user AND productID IN ($keys)";

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
                    // I hopw this wont be needed at any point
                }

            } else {

                $sql = "SELECT productID AS id FROM Wishes LEFT JOIN Visitors USING (visitorID) WHERE userId =:user";

                try {
                    $statement = $this->pdo->prepare($sql);
                    $statement->bindValue(':user', $collection->getUserId(), \PDO::PARAM_STR);
                    if ($statement->execute()) {
                        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
                        foreach ($data as$entry) {
                            $collection->addItem($entry);
                        }
                    }

                } catch (\PDOException $e) {
                    // I hopw this wont be needed at any point
                }

            }

        }


    }