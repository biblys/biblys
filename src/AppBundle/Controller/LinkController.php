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


namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Exception;
use Framework\Controller;
use Model\LinkQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;


class LinkController extends Controller
{

    /**
     * Delete a link
     * @route /links/{id}/delete
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(
        CurrentUser $currentUser,
        int $id,
    ): JsonResponse
    {
        $currentUser->authPublisher();

        LinkQuery::create()->findPk($id)->delete();

        return new JsonResponse();
    }
}
