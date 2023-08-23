<?php

namespace Model;

use Model\Base\Invitation as BaseInvitation;
use RandomLib\Factory;

/**
 * Skeleton subclass for representing a row from the 'invitations' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Invitation extends BaseInvitation
{

    public static function generateCode(): string
    {
        $randomFactory = new Factory();
        $generator = $randomFactory->getMediumStrengthGenerator();
        return $generator->generateString(8, "ABCDEFGHJKLMNPQRSTUVWXYZ23456789");
    }
}
