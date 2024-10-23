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
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ErrorController extends Controller
{
    public function exception(Exception $exception): JsonResponse
    {
        return new JsonResponse([
            "error" => [
                "exception" => get_class($exception),
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => $exception->getTrace(),
            ]
        ], $this->_getStatusForException($exception));
    }

    private function _getStatusForException(Exception $exception): int
    {
        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\BadRequestHttpException")) {
            return 400;
        }

        if (is_a($exception, UnauthorizedHttpException::class)) {
            return 401;
        }

        if (is_a($exception, AccessDeniedHttpException::class)) {
            return 403;
        }

        if (
            is_a($exception, "Symfony\Component\Routing\Exception\ResourceNotFoundException") ||
            is_a($exception, "Symfony\Component\HttpKernel\Exception\NotFoundHttpException")
        ) {
            return 404;
        }

        if (is_a($exception, ConflictHttpException::class)) {
            return 409;
        }

        return 500;
    }

}
