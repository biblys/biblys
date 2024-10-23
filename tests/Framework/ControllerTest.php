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


namespace Framework;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../setUp.php";

class ControllerTest extends TestCase
{
    public function testConstructorWithUserNull()
    {
        // given
        $GLOBALS["_V"] = null;

        // when
        new Controller();

        // then
        $this->expectNotToPerformAssertions();
    }
}
