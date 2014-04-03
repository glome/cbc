<?php

namespace Application\Controllers;


class Search extends \Application\Common\Controller
{
    public function getIndex($request)
    {

    }


    public function postIndex($request)
    {


        $shop = $this->serviceFactory->create('Shop');
        $shop->prepareSearch($request->getParameter('search-field'));

        header('Location: /search');
        exit;

    }
}
