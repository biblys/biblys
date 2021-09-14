<?php

namespace Biblys\Exception;

use Exception;
use Throwable;

class ArticleAlreadyInRayonException extends Exception
{
    public function __construct($articleTitle, $rayonName, $code = 0, Throwable $previous = null)
    {
        $message = "L'article « $articleTitle » est déjà dans le rayon « $rayonName ».";
        parent::__construct($message, $code, $previous);
    }
}