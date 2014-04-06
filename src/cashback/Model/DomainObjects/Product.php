<?php

    namespace Application\DomainObjects;

    class Product
    {

        private $id;

        public function setId($id) {
            $this->id = $id;
        }


        public function getId() {
            return $this->id;
        }


        private $title;

        public function setTitle($title)
        {
            $this->title = $title;
        }


        public function getTitle()
        {
            return $this->title;
        }

        private $image;

        public function setContent($image)
        {
            $this->setImage($image);
        }

        public function setImage($image)
        {
            $this->image = $image;
        }

        public function getImage()
        {
            return $this->image;
        }


        private $currency;
        private $currencySymbol;

        public function setCurrency($currency)
        {
            $map = [
                'EUR' => '€',
                'USD' => '$',
                'GBP' => '£',
            ];
            $this->currency = $currency;
            $this->currencySymbol = $currency;
            if (isset($map[$currency])) {
                $this->currencySymbol = $map[$currency];
            }
        }
        public function getCurrency($currency)
        {
            return $this->currency;
        }

        private $page;

        public function setPage($page)
        {
            $this->page = $page;
        }

        public function getPage()
        {
            return $this->page;
        }


        private $description;

        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getDescription()
        {
            return $this->description;
        }


        private $creationDate;

        public function setCreatedAt($date)
        {
            $this->creationDate = new \DateTime($date);
        }

        public function isNew()
        {
            $old = $this->creationDate->add(new \DateInterval('P1M'));
            $now = new \DateTime("now");

            return $now <= $old;
        }


        private $price;

        public function setLastPrice($price)
        {
            $this->price = $price;
        }

        private $bonusMoney;

        public function setBonusMoney($bonusMoney)
        {
            if ($bonusMoney !== '0.00') {
                $this->bonusMoney = $bonusMoney;
            }
        }

        public function getBonusMoney()
        {
            return $this->bonusMoney;
        }


        private $bonusPercent;

        public function setBonusPercent($bonusPercent)
        {
            $this->bonusPercent = $bonusPercent;
        }

        public function getBonusPercent()
        {
            return $this->bonusPercent;
        }


        private $link;

        public function setPersonalAction($link)
        {
            $this->link = $link;
        }

        public function getLink()
        {
            return $this->link;
        }

        public function getParsedArray()
        {

            $onebonus = ($this->bonusMoney === null && $this->bonusPercent !== null) ||
                        ($this->bonusMoney !== null && $this->bonusPercent === null);



            return [
                'one-bonus' => $onebonus,
                'new' => $this->isNew(),
                'id' => $this->id,
                'title' => $this->title,
                'image' => $this->image,
                'currencySymbol' => $this->currencySymbol,
                'price' => $this->price,
                'description' => $this->description,
                'bonusMoney' => $this->bonusMoney,
                'bonusPercent' => $this->bonusPercent,
                'link' => $this->link,
            ];
        }


    }