<?php

namespace Application\Views;

class Profile extends \Application\Common\View
{
    private $currentTab = 'wishlist';
    private $code;

    public function index()
    {
        return $this->wishlist();
    }

    public function wishlist()
    {
        $sync = $this->serviceFactory->create('Sync');
        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $categories = $shop->getCategories();

        $translation = $this->serviceFactory->create('Translation');
        $translation->checkSettings();

        $builder = $this->templateBuilder;

        $main    = $builder->create('main');
        $content = $builder->create('profile');
        $footer  = $builder->create('footer');
        $profile = $builder->create('profile-brief');
        $deals   = $builder->create('deals');

        $content->assign('categories', array_slice($categories, 0, 10));
        $footer->assign('categories', $categories);
        $deals->assign('products', $shop->getRecommendations(4));

        $code = $sync->getPairingCode();

        $error = null;
        if ($sync->getSync()) {
            # there was a sync code from an other CBC posted
            $error['code'] = $sync->getSync()->getErrorCode();
            $error['message'] = $sync->getSync()->getErrorMessage();
        }

        $brothers = $sync->getBrothers();

        $profile->assignAll([
            'wishes'        => $itinerary->getWishlistLength(),
            'earnings'      => $itinerary->getEarnings('EUR'),
            'currencies'    => $configuration->getListOf('currencies'),
            'current'       => [
                'currency'  => $configuration->getPreferredCurrency(),
            ],
            'total'         => $itinerary->getTotalEarnings(),
            'opened'        => true,
        ]);

        $content->assignAll([
            'deals'      => $deals,
            'products'   => $itinerary->getWishlist(),
            'history'    => $itinerary->getHistory('EUR'),
            'currency'   => $configuration->getPreferredCurrency(),
            'tab'        => $this->currentTab,
            'code'       => str_split($code, 4),
            'error'      => $error,
            'brothers'   => $brothers,
            'myself'     => $sync->getUser()->getId(),
        ]);

        $main->assignAll([
            'content'  => $content,
            'user'     => $profile,
            'footer'   => $footer,
            'settings' => $settings,
            'currencies' => $configuration->getListOf('currencies'),
            'current'    => [
                'currency'    => $configuration->getPreferredCurrency(),
                'language'    => $configuration->getPreferredLanguage(),
            ],
        ]);

        return $main->render();
    }

    public function history()
    {
        $this->currentTab = 'history';
        return $this->wishlist();
    }

    public function wish()
    {

    }

    public function spurn()
    {

    }

    public function pairing()
    {
        $this->currentTab = 'pairing';
        return $this->wishlist();
    }

    public function qr()
    {
        $code = $_SESSION['glome.code'];

        if ($code) {
            header('Content-type: image/png');

            $qrCode = new \Endroid\QrCode\QrCode();
            $qrCode->setText($code);
            $qrCode->setSize(100);
            $qrCode->setPadding(10);
            $qrCode->render();
        }
    }
}
