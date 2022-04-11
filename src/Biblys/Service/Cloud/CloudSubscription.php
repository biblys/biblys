<?php

namespace Biblys\Service\Cloud;

class CloudSubscription
{
    /**
     * @var string
     */
    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function hasExpired(): bool
    {
        return $this->status !== "active";
    }

    public function isExpiringSoon(): bool
    {
        return false;
    }
}
