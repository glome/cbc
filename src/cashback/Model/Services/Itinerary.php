<?php

namespace Application\Services;


class Itinerary extends \Application\Common\Service
{


    private $currentUser;

    public function forUser($user)
    {
        $this->currentUser = $user;
    }

    public function addWish($productId)
    {
        $wish = $this->domainObjectFactory->create('Wish');
        $wishMapper = $this->dataMapperFactory->create('Wish', 'SQL');

        $visitorMapper = $this->dataMapperFactory->create('Visitor', 'SQL');
        $visitorMapper->store($this->currentUser);

        $wish->setVisitorId($this->currentUser->getVisitorId());
        $wish->setProductId($productId);

        $wishMapper->store($wish);
    }

    public function removeWish($productId)
    {
        $wish = $this->domainObjectFactory->create('Wish');
        $mapper = $this->dataMapperFactory->create('Wish', 'SQL');

        $visitorMapper = $this->dataMapperFactory->create('Visitor', 'SQL');
        $visitorMapper->store($this->currentUser);

        $wish->setVisitorId($this->currentUser->getVisitorId());
        $wish->setProductId($productId);

        $mapper->delete($wish);
    }



    public function getWishlist()
    {

        $wishlist = $this->domainObjectFactory->create('ProductCollection');
        $db = $this->dataMapperFactory->create('ProductCollection', 'SQL');
        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');



        $wishlist->setUserId($this->currentUser->getId());
        $db->fetch($wishlist);
        $api->fetch($wishlist);


        return $wishlist->getParsedArray();
    }


    public function getWishlistLength()
    {
        $wishlist = $this->domainObjectFactory->create('ProductCollection');
        $db = $this->dataMapperFactory->create('ProductCollection', 'SQL');
        $wishlist->setUserId($this->currentUser->getId());
        $db->fetch($wishlist);

        return $wishlist->getAmount();
    }

}