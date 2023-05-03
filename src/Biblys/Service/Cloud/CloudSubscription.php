<?php

namespace Biblys\Service\Cloud;

class CloudSubscription
{
    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function isActive(): bool
    {
        return $this->status === "active";
    }
}
