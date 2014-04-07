<?php

    namespace Application\DataMappers\SQL;

    class RecommendationCollection extends \Application\Common\SQLMapper
    {

        public function fetch($collection)
        {

            $count = 0;
            $list = [];

            $sql = 'SELECT
                        productId AS id,
                        COUNT(productId) AS total
                    FROM VisitorViews
                    GROUP BY VisitorViews.productId
                    ORDER BY total
                    LIMIT 20';
            $statement = $this->pdo->query($sql);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $item) {
                $total = $item['total'];
                $list[$item['id']] = isset($list[$item['id']]) ? $list[$item['id']] + $total : $total ;
            }

            $sql = 'SELECT
                        productId AS id,
                        COUNT(productId) AS total
                    FROM Wishes
                    GROUP BY Wishes.productId
                    ORDER BY total
                    LIMIT 20';
            $statement = $this->pdo->query($sql);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $item) {
                $total = $item['total'] * 1.2;
                $list[$item['id']] = isset($list[$item['id']]) ? $list[$item['id']] + $total : $total ;
            }

            $sql = 'SELECT
                        productId AS id,
                        COUNT(productId) AS total
                    FROM VisitorRedirects
                    GROUP BY VisitorRedirects.productId
                    ORDER BY total
                    LIMIT 20';
            $statement = $this->pdo->query($sql);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($data as $item) {
                $total = $item['total'] * 1.5;
                $list[$item['id']] = isset($list[$item['id']]) ? $list[$item['id']] + $total : $total ;
            }



            if ($collection->hasItems()) {
                foreach ($collection as $product) {
                    $id = $product->getId();
                    if (isset($list[$id])) {
                        $product->markAsPopular();
                    }
                }

            } else {
                arsort($list);

                $limit = $collection->getLimit();

                foreach ($list as $id => $value) {
                    $count += 1;
                    $collection->addItem(['id' => $id, 'score' => $value]);
                    if ($count === $limit) {
                        return;
                    }
                }
            }

        }


        public function store($instance)
        {

        }


        public function delete($instance)
        {

        }

    }