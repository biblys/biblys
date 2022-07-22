<?php

namespace Framework\Exception;

/**
 * @deprecated Use UnauthorizedHttpException for 401 and AccessDeniedHttpException for 403
 */
class AuthException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) 
    {
        parent::__construct($message, $code, $previous);
    }
}
