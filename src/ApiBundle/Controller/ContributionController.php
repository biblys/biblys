<?php

namespace ApiBundle\Controller;

use Framework\Controller;
use Framework\Exception\AuthException;
use Model\Role;
use Model\RoleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContributionController extends Controller
{

    /**
     * @throws PropelException
     * @throws AuthException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        self::authAdmin($request);

        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);
        $jobId = $params["job_id"];

        $contribution = RoleQuery::create()->findPk($id);
        $contribution->setJobId($jobId);
        $contribution->save();

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse(["authors" => $authorNamesAsString]);
    }
}
