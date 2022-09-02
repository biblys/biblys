<?php

namespace Biblys\Service;

use Biblys\Exception\InvalidEmailAddressException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require_once __DIR__."/../../setUp.php";

class MailerTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testInvalidToEmail()
    {
        $this->expectException(InvalidEmailAddressException::class);
        $this->expectExceptionMessage("L'adresse customer.4.@biblys.fr est invalide.");

        // given
        $mailer = new Mailer();

        // when
        $mailer->send(
            "customer.4.@biblys.fr",
            "Hello !",
            "How are you?"
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testInvalidFromEmail()
    {
        // then
        $this->expectException(InvalidEmailAddressException::class);
        $this->expectExceptionMessage("L'adresse vendor.4.@biblys.fr est invalide.");

        // given
        $mailer = new Mailer();

        // when
        $mailer->send(
            "customer@biblys.fr",
            "Hello !",
            "How are you?",
            ["vendor.4.@biblys.fr" => "vendor"]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testInvalidReplyToEmail()
    {
        $this->expectException(InvalidEmailAddressException::class);
        $this->expectExceptionMessage("L'adresse yes-reply.4.@biblys.fr est invalide.");

        // given
        $mailer = new Mailer();

        // when
        $mailer->send(
            "customer@biblys.fr",
            "Hello !",
            "How are you?",
            ["vendor@biblys.fr" => "vendor"],
            ["reply-to" => "yes-reply.4.@biblys.fr"]
        );
    }
}
