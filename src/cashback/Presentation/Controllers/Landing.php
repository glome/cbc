<?php

namespace Application\Controllers;

class Landing extends \Application\Common\Controller
{
    public function getIndex($request = false)
    {
        $this->checkLocked();

        $shop = $this->serviceFactory->create('Shop');
        $itinerary = $this->serviceFactory->create('Itinerary');

        $shop->forUser($this->user);
        $itinerary->forUser($this->user);

        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->setSearchState(0);
    }

    public function postIndex($request)
    {
        $configuration = $this->serviceFactory->create('Configuration');
        $contacts = $configuration->getContacts();

        $transport = \Swift_SendmailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);

        $email = filter_var($request->getParameter('email'), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $email = 'anonymous@glome.me';
        }

        $message = \Swift_Message::newInstance($contacts['subject'])
          ->setFrom([$email])
          ->setTo($contacts['address'])
          ->setBody($request->getParameter('email'));

        $numSent = $mailer->send($message);

        echo json_encode(['message' => 'ok']);

        exit;
    }
}
