<?php


namespace Biblys\Service\Validator;


use Exception;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class Validator
{
    /**
     * @throws ValidatorException
     */
    public static function validate(ActiveRecordInterface $item)
    {
        $isValid = $item->validate();
        if (!$isValid) {
            $failures = $item->getValidationFailures();
            throw new ValidatorException($failures[0]);
        }
    }
}