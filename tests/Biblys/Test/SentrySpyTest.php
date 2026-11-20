<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Biblys\Test;

use Exception;
use PHPUnit\Framework\TestCase;

use function Sentry\captureException;

class SentrySpyTest extends TestCase
{
    public function testReportsNothingWhenNoExceptionWasCaptured()
    {
        // given
        $sentry = SentrySpy::install();

        // when
        $messages = $sentry->getReportedExceptionMessages();

        // then
        $this->assertEquals([], $messages);
    }

    public function testReportsCapturedExceptionsInOrder()
    {
        // given
        $sentry = SentrySpy::install();

        // when
        captureException(new Exception("First error"));
        captureException(new Exception("Second error"));

        // then
        $this->assertEquals(
            ["First error", "Second error"],
            $sentry->getReportedExceptionMessages()
        );
    }

    public function testDoesNotReportExceptionsCapturedBeforeInstall()
    {
        // given
        SentrySpy::install();
        captureException(new Exception("Old error"));

        // when
        $sentry = SentrySpy::install();

        // then
        $this->assertEquals([], $sentry->getReportedExceptionMessages());
    }
}
