<?php

namespace Application\Services;


class Itinerary extends \Application\Common\Service
{

    public function addWish($user, $productId)
    {
        $wish = $this->domainObjectFactory->create('Wish');
        $wishMapper = $this->dataMapperFactory->create('Wish', 'SQL');

        $visitorMapper = $this->dataMapperFactory->create('Visitor', 'SQL');

        $visitorMapper->store($user);

        $wish->setVisitorId($user->getVisitorId());
        $wish->setProductId($productId);

        $wishMapper->store($wish);
    }

    public function removeWish($user, $productId)
    {
        $wish = $this->domainObjectFactory->create('Wish');
        $mapper = $this->dataMapperFactory->create('Wish', 'SQL');

        $wish->setUserId($user->getId());
        $wish->setProductId($productId);

        $mapper->delete($wish);
    }

}