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


namespace ApiBundle\Controller;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . "/../../setUp.php";

class ErrorControllerTest extends TestCase
{
    /** 400 */
    public function testBadRequestExceiption()
    {
        // given
        $controller = new ErrorController();
        $exception = new BadRequestHttpException("That request is bad, man.");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            400,
            $response->getStatusCode(),
            "it should response with HTTP status 400"
        );
        $this->assertEquals(
            "That request is bad, man.",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 401 */
    public function testUnauthorizedException()
    {
        // given
        $controller = new ErrorController();
        $exception = new UnauthorizedHttpException("","Who ARE you ?");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            401,
            $response->getStatusCode(),
            "it should response with HTTP status 401"
        );
        $this->assertEquals(
            "Who ARE you ?",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 403 */
    public function testForbiddenException()
    {
        // given
        $controller = new ErrorController();
        $exception = new AccessDeniedHttpException("Can't touch this");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            403,
            $response->getStatusCode(),
            "it should response with HTTP status 403"
        );
        $this->assertEquals(
            "Can't touch this",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 404 (Routing) */
    public function testResourceNotFoundException()
    {
        // given
        $controller = new ErrorController();
        $exception = new ResourceNotFoundException("Resource not found");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
        $this->assertEquals(
            "Resource not found",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 404 (HttpKernel) */
    public function testNotFoundHttpException()
    {
        // given
        $controller = new ErrorController();
        $exception = new NotFoundHttpException("Not found HTTP");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            404,
            $response->getStatusCode(),
            "it should response with HTTP status 404"
        );
        $this->assertEquals(
            "Not found HTTP",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 409 */
    public function testConflictException()
    {
        // given
        $controller = new ErrorController();
        $exception = new ConflictHttpException("Entity already exists.");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            409,
            $response->getStatusCode(),
            "it should response with HTTP status 409"
        );
        $this->assertEquals(
            "Entity already exists.",
            $json->error->message,
            "it should display error message"
        );
    }

    /** 500 */
    public function testHandleServerError()
    {
        // given
        $controller = new ErrorController();
        $exception = new Exception("Internal server error");

        // when
        $response = $controller->exception($exception);

        // then
        $json = json_decode($response->getContent());
        $this->assertEquals(
            500,
            $response->getStatusCode(),
            "it should response with HTTP status 500"
        );
        $this->assertEquals(
            "Internal server error",
            $json->error->message,
            "it should display error message"
        );
        $this->assertEquals(
            "Exception",
            $json->error->exception,
        );
    }
}