<?php

namespace Biblys\Service\Cloud;

use DateTime;

class CloudSubscription
{
    /**
     * @var DateTime
     */
    private $expirationDate;

    /**
     * @var int
     */
    private $daysUntilDue;

    public function __construct(int $expiresAt, ?int $daysUntilDue)
    {
        $this->expirationDate = new DateTime();
        $this->expirationDate->setTimestamp($expiresAt);
        $this->daysUntilDue = $daysUntilDue;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function getDaysUntilDue(): ?int
    {
        return $this->daysUntilDue;
    }

    public function hasExpired(): bool
    {
        $daysUntilDue = $this->getDaysUntilDue();

        if ($daysUntilDue === null) {
            return false;
        }

        if ($daysUntilDue > 0) {
            return false;
        }

        return true;
    }

    public function isExpiringSoon(): bool
    {
        $daysUntilDue = $this->getDaysUntilDue();

        if ($daysUntilDue === null) {
            return false;
        }

        if ($this->hasExpired()) {
            return false;
        }

        if ($daysUntilDue < 7) {
            return false;
        }

        return true;
    }
}
