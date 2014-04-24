<?php

namespace Application\Common;

class SQLMapper
{

    protected $pdo;

    public function setConnection($pdo)
    {
        $this->pdo = $pdo;
    }
}
