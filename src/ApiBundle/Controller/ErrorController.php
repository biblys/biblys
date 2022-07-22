<?php

namespace ApiBundle\Controller;

use Exception;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

        return 500;
    }

}
