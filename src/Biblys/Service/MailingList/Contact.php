<?php

namespace Biblys\Service\MailingList;

class Contact
{
    private string $email;
    private string $createdAt;

    public function __construct(string $email, string $createdAt)
    {
        $this->email = $email;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
