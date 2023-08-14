<?php

namespace Biblys\Service\Validator;

use Exception;
use Model\AxysAccount;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testValidate()
    {
        // then
        $this->expectException("Biblys\Service\Validator\ValidatorException");
        $this->expectExceptionMessage(
            "La validation de la propriété “email” a échoué pour la valeur « invalid-email-example.net » : This value is not a valid email address."
        );

        // given
        $user = new AxysAccount();
        $user->setEmail("invalid-email-example.net");

        // when
        Validator::validate($user);
    }
}
