<?php

namespace Framework\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * @deprecated Use UnauthorizedHttpException for 401 and AccessDeniedHttpException for 403
 */
class AuthException extends HttpException
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct(statusCode: 401, message: $message, previous: $previous, code: $code);
    }
}
