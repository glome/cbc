<?php

namespace Application\DomainObjects;

class IncentiveCollection extends \Application\Common\Collection
{


    protected function buildItem()
    {
        return new Incentive;
    }


    public function locateIncentive($id)
    {
        foreach ($this as $incentive) {
            $current = $incentive->getId();
            if ($current == $id) {
                return $incentive;
            }
        }

        return false;
    }
}
