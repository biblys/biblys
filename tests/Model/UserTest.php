<?php

namespace Model;

use Biblys\Test\Factory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class UserTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteWithNonAdmin()
    {
        // when
        $site = Factory::createSite();
        $user = Factory::createUser();

        // when
        $isAdmin = $user->isAdminForSite($site);

        // then
        $this->assertFalse(
            $isAdmin,
            "it should return false if user is no admin"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteWithAdmin()
    {
        // when
        $site = Factory::createSite();
        $user = Factory::createAdminUser($site);

        // when
        $isAdmin = $user->isAdminForSite($site);

        // then
        $this->assertTrue(
            $isAdmin,
            "it should return true if user is admin"
        );
    }

    /**
     * @throws PropelException
     */
    public function testValidatingValidUser()
    {
        // given
        $user = new User();
        $user->setEmail("valid-email@example.net");

        // when
        $saved = $user->save();

        // then
        $this->assertEquals(1, $saved, "it should have saved 1 user");
        $this->assertEquals(
            "valid-email@example.net",
            $user->getEmail(),
            "it should save without throwing an exception"
        );
    }

    /**
     * @throws PropelException
     */
    public function testValidatingUserWithInvalidEmail()
    {
        // then
        $this->expectException("Biblys\Service\Validator\ValidatorException");
        $this->expectExceptionMessage(
            "La validation de la propriété “email” a échoué pour la valeur « invalid-email-example.net » : This value is not a valid email address."
        );

        // given
        $user = new User();
        $user->setEmail("invalid-email-example.net");

        // when
        $user->save();
    }
}