<?php

namespace Application\Controllers;


class Options extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->applyParam($request->getParameter('param'));
        $configuration->delParam($request->getParameter('del'));
    }

    public function getDownload($request)
    {
        $id = $request->getParameter('id');
        if ((int)$id === 1) {
            $configuration = $this->serviceFactory->create('Configuration');
            $configuration->disableDownlaodBar();
        }
    }

    public function getSearch($request)
    {
        $id = $request->getParameter('id');
        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->setSearchState($id);
    }
}
