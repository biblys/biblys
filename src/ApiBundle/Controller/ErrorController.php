<?php

namespace ApiBundle\Controller;

use Exception;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController extends Controller
{
    public function exception(Exception $exception): JsonResponse
    {
        return new JsonResponse([
            "error" => [
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => $exception->getTrace(),
            ]
        ], $this->_getStatusForException($exception));
    }

    private function _getStatusForException(Exception $exception): int
    {
        if (is_a($exception, "Symfony\Component\Routing\Exception\ResourceNotFoundException")) {
            return 404;
        }

        return 500;
    }

}
