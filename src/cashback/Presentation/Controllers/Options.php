<?php

namespace Application\Controllers;


class Options extends \Application\Common\Controller
{
    public function getIndex($request)
    {
        $configuration = $this->serviceFactory->create('Configuration');
        $configuration->applyParam($request->getParameter('param'));
    }

    public function getDownload($request)
    {
        $id = $request->getParameter('id');
        if ((int)$id === 1) {
            $configuration = $this->serviceFactory->create('Configuration');
            $configuration->disableDownlaodBar();
        }
    }

}
