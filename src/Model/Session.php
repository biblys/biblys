<?php

namespace Model;

use DateTime;
use Model\Base\Session as BaseSession;
use Propel\Runtime\Exception\PropelException;
use RandomLib\Factory;
use RandomLib\Generator;

/**
 * Skeleton subclass for representing a row from the 'session' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Session extends BaseSession
{
    /**
     * @throws PropelException
     */
    public static function buildForUser(User $user): Session
    {
        $session = new Session();
        $session->setUser($user);
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('tomorrow'));
        return $session;
    }

    public static function generateToken(): string
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(32, Generator::CHAR_ALNUM);
    }
}
