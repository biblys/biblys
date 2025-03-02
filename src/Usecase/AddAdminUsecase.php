<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace Usecase;

use Biblys\Service\CurrentSite;
use Model\Right;
use Model\RightQuery;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;

class AddAdminUsecase
{
    public function __construct(private readonly CurrentSite $currentSite) {}

    /**
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(string $userEmail): array
    {
        $userWasCreated = false;

        $user = UserQuery::create()
            ->filterBySite($this->currentSite->getSite())
            ->findOneByEmail($userEmail);
        if (!$user) {
            $user = new User();
            $user->setEmail($userEmail);
            $user->setSite($this->currentSite->getSite());
            $user->save();
            $userWasCreated = true;
        }

        $isUserAlreadyAdmin = RightQuery::create()->isUserAdmin($user);
        if ($isUserAlreadyAdmin) {
            throw new BusinessRuleException("L'utilisateur $userEmail a déjà un accès administrateur.");
        }

        $right = new Right();
        $right->setUser($user);
        $right->setSite($this->currentSite->getSite());
        $right->setIsAdmin(true);
        $right->save();

        return [$userWasCreated, $user];
    }
}