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
        $syncService = $this->serviceFactory->create('Sync');

        $itinerary = $this->serviceFactory->create('Itinerary');
        $configuration = $this->serviceFactory->create('Configuration');
        $settings = $configuration->getCurrentSettings();

        $shop = $this->serviceFactory->create('Shop');
        $translation = $this->serviceFactory->create('Translation');

        $builder = $this->templateBuilder;

        $main    = $builder->create('main');
        $content = $builder->create('profile');
        $footer  = $builder->create('footer');

        $earnings = 0;
        $categories = [];
        $deals = $brothers = $code = $error = $wishlist = $history = null;

        $profile = $builder->create('profile-brief');
        $profile->assign('locked', $syncService->getUser()->isLocked());

        if ($syncService->getUser()->getErrorCode()) {
            # some error during login?
            $error = $syncService->getUser()->getError();
            $code = $syncService->getUnlockCode();
        } else {
            $sync = $syncService->getSync();
            $code = $syncService->getPairingCode();
            if ($sync) {
                # there was a sync code from an other CBC posted
                $error = $sync->getError();
            }

            $brothers = $syncService->getBrothers();
            $categories = $shop->getCategories();
            $earnings = $itinerary->getTotalEarnings();
            $wishlist = $itinerary->getWishlist();
            $history = $itinerary->getHistory();

            $translation->checkSettings();

            $deals   = $builder->create('deals');

            $deals->assign('products', $shop->getRecommendations(4));

            $profile->assignAll([
                'wishes'        => $itinerary->getWishlistLength(),
                'earnings'      => $itinerary->getEarnings('EUR'),
                'currencies'    => $configuration->getListOf('currencies'),
                'current'       => [
                    'currency'  => $configuration->getPreferredCurrency(),
                ],
                'total'         => $earnings,
                'opened'        => true,
            ]);
        }

        $content->assign('categories', array_slice($categories, 0, 10));
        $footer->assign('categories', $categories);

        $sync_url = 'http://' . $_SERVER['HTTP_HOST'] . '/profile/pairing?code=' . $code;

        $content->assignAll([
            'deals'      => $deals,
            'products'   => $wishlist,
            'history'    => $history,
            'currency'   => $configuration->getPreferredCurrency(),
            'tab'        => $this->currentTab,
            'code'       => str_split($code, 4),
            'error'      => $error,
            'brothers'   => $brothers,
            'myself'     => $syncService->getUser()->getId(),
            'locked'     => $syncService->getUser()->isLocked(),
            'sync_url'   => $sync_url,
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
            'locked'     => $syncService->getUser()->isLocked(),
            'imtoken'    => $shop->getUser()->getMessagingToken() || $_COOKIE['messaging']
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

    public function locking()
    {
        $this->currentTab = 'locking';
        return $this->wishlist();
    }

    public function unlocking()
    {
        $this->currentTab = 'unlocking';
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
