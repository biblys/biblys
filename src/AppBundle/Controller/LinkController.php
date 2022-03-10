<?php

namespace AppBundle\Controller;

use Exception;
use Framework\Controller;

use Framework\Exception\AuthException;
use LinkManager;
use Model\LinkQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class LinkController extends Controller
{

    /**
     * Delete a link
     * @route /links/{id}/delete
     * @param Request $request
     * @param int $id The id of to the link to be deleted
     * @return JsonResponse
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(Request $request, int $id): JsonResponse
    {
        self::authPublisher($request, null);

        LinkQuery::create()->findPk($id)->delete();

        return new JsonResponse();
    }
}
