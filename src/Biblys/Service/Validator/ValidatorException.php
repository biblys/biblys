<?php

namespace Biblys\Service\Validator;

use Exception;
use Symfony\Component\Validator\ConstraintViolation;

class ValidatorException extends Exception
{
    public function __construct(ConstraintViolation $violation)
    {
        $errorMessage =
            sprintf(
                "La validation de la propriété “%s” a échoué pour la valeur « %s » : %s",
                $violation->getPropertyPath(),
                $violation->getInvalidValue(),
                $violation->getMessageTemplate()
            );

        parent::__construct($errorMessage);
    }

}