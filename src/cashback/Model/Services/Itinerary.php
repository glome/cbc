<?php

namespace Application\Services;


class Itinerary extends \Application\Common\Service
{


    private $currentUser;
    private $finances = null;

    public function forUser($user)
    {
        $this->currentUser = $user;
    }


    private function acquireProduct($id)
    {
        $product = $this->domainObjectFactory->create('Product');
        $api = $this->dataMapperFactory->create('Product', 'REST');

        $product->setId($id);
        $api->fetch($product);

        return $product;
    }


    public function addWish($productId)
    {
        $product = $this->acquireProduct($productId);

        $wish = $this->domainObjectFactory->create('Wish');
        $wishMapper = $this->dataMapperFactory->create('Wish', 'SQL');

        $visitorMapper = $this->dataMapperFactory->create('Visitor', 'SQL');
        $visitorMapper->store($this->currentUser);

        $wish->setVisitorId($this->currentUser->getVisitorId());
        $wish->setProductId($productId);
        $wish->setCategoryId($product->getCategoryId());

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

        $api = $this->dataMapperFactory->create('ProductCollection', 'REST');
        $api->fetch($wishlist);

        return $wishlist->getAmount();
    }


    private function acquireFinanceDetails()
    {
        $id = $this->currentUser->getId();
       // $id = 'cd_65631d_1e12f98457926c4f06cc13f670ef09e5';
        $finances = $this->domainObjectFactory->create('Finances');
        $api = $this->dataMapperFactory->create('Finances', 'REST');
        $finances->setUserId($id);
        $api->fetch($finances);
//        var_dump($finances);
        return $finances;
    }

    public function getEarnings($currency)
    {
        $finances = $this->finances;

        if ($finances === null) {
            $finances = $this->acquireFinanceDetails();
            $this->finances = $finances;
        }


        $total = 0;
        if (!$finances->hasError()) {
            $total = $finances->getTotal($currency);
        }
        return number_format($total/100, 2 , '.', '');
    }

    public function getHistory($currency)
    {
        $finances = $this->finances;

        if ($finances === null) {
            $finances = $this->acquireFinanceDetails();
            $this->finances = $finances;
        }

        if (!$finances->hasError()) {
            $backlog = $finances->getBacklog($currency);
        } else {
            $backlog = ['error' => $finances->getErrorMessage()];
        }

        return $backlog;
    }


    public function getRedeemMessage()
    {
        $wallet = $this->domainObjectFactory->create('Wallet');
        $api = $this->dataMapperFactory->create('Wallet', 'REST');
        $wallet->setUserId($this->currentUser->getId());
        $api->fetch($wallet);

        return $wallet->getParsedArray();

    }



    public function getTotalEarnings()
    {

    }

}