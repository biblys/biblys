<?php

namespace Biblys\Exception;

use Entity;
use Exception;

class InvalidEntityFetchedException extends Exception
{
    private $invalidEntity = null;

    public function __construct($message = "", string $entityType, Entity $invalidEntity)
    {
        $message = sprintf("%s %s is invalid: $message", $entityType, $invalidEntity->get("id"));
        parent::__construct($message, 0, null);

        $this->invalidEntity = $invalidEntity;
    }

    public function getInvalidEntity(): Entity
    {
        return $this->invalidEntity;
    }
}