<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;

require_once __DIR__."/../../setUp.php";

class MailerTest extends TestCase
{
    public function testInvalidToEmail()
    {
        $this->expectException("InvalidArgumentException");
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

    public function testInvalidFromEmail()
    {
        $this->expectException("InvalidArgumentException");
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

    public function testInvalidReplyToEmail()
    {
        $this->expectException("InvalidArgumentException");
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
