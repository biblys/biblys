<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Service;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Legacy\LegacyCodeHelper;
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
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());

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
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());

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
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());

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
