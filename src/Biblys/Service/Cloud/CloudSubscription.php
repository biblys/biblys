<?php

namespace Biblys\Service\Cloud;

class CloudSubscription
{
    private string $status;
    private bool $isPaid;

    public function __construct(string $status, bool $isPaid = false)
    {
        $this->status = $status;
        $this->isPaid = $isPaid;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === "active";
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
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
