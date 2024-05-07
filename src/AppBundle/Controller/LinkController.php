<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\LinkQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;


class LinkController extends Controller
{

    /**
     * Delete a link
     * @route /links/{id}/delete
     * @throws AuthException
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
