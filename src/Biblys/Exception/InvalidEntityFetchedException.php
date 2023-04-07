<?php

namespace Biblys\Exception;

use Entity;
use Exception;

class InvalidEntityFetchedException extends Exception
{
    private Entity $invalidEntity;

    public function __construct($message, string $entityType, Entity $invalidEntity)
    {
        $message = sprintf("%s %s is invalid: $message", $entityType, $invalidEntity->get("id"));
        parent::__construct($message);

        $this->invalidEntity = $invalidEntity;
    }

    public function getInvalidEntity(): Entity
    {
        return $this->invalidEntity;
    }
}