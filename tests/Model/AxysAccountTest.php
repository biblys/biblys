<?php

namespace Model;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class AxysAccountTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteWithNonAdmin()
    {
        // when
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser();

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
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser($site);

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
    public function testHasRightForPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherUser($publisher);

        // when
        $hasRightForPublisher = $user->hasRightForPublisher($publisher);

        // then
        $this->assertTrue(
            $hasRightForPublisher,
            "should return true if user has right for given publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasRightForPublisherWithoutPublisherRight()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createUser();

        // when
        $hasRightForPublisher = $user->hasRightForPublisher($publisher);

        // then
        $this->assertFalse(
            $hasRightForPublisher,
            "should return false if user has no publisher right"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRight()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherUser($publisher);

        // when
        $hasPublisherRight = $user->hasPublisherRight();

        // then
        $this->assertTrue(
            $hasPublisherRight,
            "should return true if user has right for any publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightForNonPublisherUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createUser();

        // when
        $hasPublisherRight = $user->hasPublisherRight();

        // then
        $this->assertFalse(
            $hasPublisherRight,
            "should return false if user has no publisher right"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasRightForPublisherWithRightForAnotherPublisher()
    {
        // given
        $userPublisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherUser($userPublisher);

        // when
        $hasRightForPublisher = $user->hasRightForPublisher($otherPublisher);

        // then
        $this->assertFalse(
            $hasRightForPublisher,
            "should return false if user has right for another publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetCurrentRight()
    {
        // given
        $user = ModelFactory::createUser();
        $currentRight = new Right();
        $currentRight->setCurrent(true)->setAxysAccount($user);
        $currentRight->save();
        $otherRight = new Right();
        $otherRight->setCurrent(false)->setAxysAccount($user);
        $otherRight->save();

        // when
        $returnedRight = $user->getCurrentRight();

        //
        $this->assertEquals($currentRight->getId(), $returnedRight->getId());
    }

    /**
     * @throws PropelException
     */
    public function testValidatingValidUser()
    {
        // given
        $user = new AxysAccount();
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
        $user = new AxysAccount();
        $user->setEmail("invalid-email-example.net");

        // when
        $user->save();
    }
}