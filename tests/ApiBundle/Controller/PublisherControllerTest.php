<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace ApiBundle\Controller;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublisherControllerTest extends TestCase
{
    /** Get */

    /**
     * @throws PropelException
     */
    public function testGetAction()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditeur Unique");
        $controller = new PublisherController();

        // when
        $response = $controller->getAction($publisher->getId());

        // then
        $json = json_decode($response->getContent(), true);
        $this->assertEquals([
            "id" => $publisher->getId(),
            "name" => 'Éditeur Unique',
        ],
        $json);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetActionIfPublisherDoesNotExist()
    {
        // given
        $controller = new PublisherController();

        // when
        $error = Helpers::runAndCatchException(
            fn () => $controller->getAction(999)
        );

        // then
        $this->assertInstanceOf(NotFoundHttpException::class, $error);
    }
}
