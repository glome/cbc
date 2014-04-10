<?php

    namespace Application\DomainObjects;

    class Product
    {

        private $id;
        private $title;
        private $image;
        private $currency;
        private $currencySymbol;
        private $page;
        private $description;
        private $creationDate;
        private $price;
        private $bonusMoney;
        private $bonusPercent;
        private $link;
        private $isFavorite = false;
        private $isPopular = false;
        private $categoryId;
        private $score = 0;
        private $advertiserId;
        private $advertiserName;
        private $programId;
        private $incentivesData;
        private $incentive;


        public function setScore($score)
        {
            $this->score = $score;
        }

        public function getScore()
        {
            return $this->score;
        }

        public function markAsFavorite()
        {
            $this->isFavorite = true;
        }

        public function markAsPopular()
        {
            $this->isPopular = true;
        }



        public function setId($id) {
            $this->id = $id;
        }


        public function getId() {
            return $this->id;
        }



        public function setTitle($title)
        {
            $this->title = $title;
        }


        public function getTitle()
        {
            return $this->title;
        }


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


        public function setPage($page)
        {
            $this->page = $page;
        }

        public function getPage()
        {
            return $this->page;
        }



        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getDescription()
        {
            return $this->description;
        }



        public function setCreatedAt($date)
        {
            $this->creationDate = new \DateTime($date);
        }

        public function isNew()
        {
            if ($this->creationDate === null) {
                return false;
            }
            $old = $this->creationDate->add(new \DateInterval('P1M'));
            $now = new \DateTime("now");

            return $now <= $old;
        }


        public function setProgramId($id)
        {
            $this->programId = $id;
        }

        public function getProgramId()
        {
            return $this->programId;
        }



        public function setLastPrice($price)
        {
            $this->price = $price;
        }


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



        public function setBonusPercent($bonusPercent)
        {
            $this->bonusPercent = $bonusPercent;
        }

        public function getBonusPercent()
        {
            return $this->bonusPercent;
        }



        public function setPersonalAction($link)
        {
            $this->link = $link;
        }

        public function getLink()
        {
            return $this->link;
        }


        public function setCategoryId($id)
        {
            $this->categoryId = $id;
        }

        public function getCategoryId()
        {
            return $this->categoryId;
        }


        public function setAdvertiser($list)
        {
            $this->advertiserId = array_shift($list);
        }


        public function associateRetailer($list)
        {
            if (array_key_exists($this->advertiserId, $list)) {
                $this->advertiserName = $list[$this->advertiserId]['name'];
            }
        }


        public function setIncentives($list)
        {
            if (count($list)){
                $this->incentivesData = array_shift($list);
            }
        }


        public function applyIncentive($incentive)
        {
            $this->incentive = $incentive;
        }


        public function getIncentiveId()
        {
            if (isset($this->incentivesData['id'])) {
                return $this->incentivesData['id'];
            }
            return false;
        }

        public function getParsedArray()
        {

            $onebonus = ($this->bonusMoney === null && $this->bonusPercent !== null) ||
                        ($this->bonusMoney !== null && $this->bonusPercent === null);



            return [
                'one-bonus' => $onebonus,
                'is-new' => $this->isNew(),
                'is-favorite' => $this->isFavorite,
                'is-popular' => $this->isPopular,
                'id' => $this->id,
                'category' => $this->categoryId,
                'title' => $this->title,
                'image' => $this->image,
                'currencySymbol' => $this->currencySymbol,
                'price' => $this->price,
                'description' => $this->description,
                'bonusMoney' => $this->bonusMoney,
                'bonusPercent' => $this->bonusPercent,
                'link' => $this->link,
                'advertiser' => $this->advertiserName,
                'incentive' => ($this->incentive) ? $this->incentive->getParsedArray() : false,
            ];
        }



    }