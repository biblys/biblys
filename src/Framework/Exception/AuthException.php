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
