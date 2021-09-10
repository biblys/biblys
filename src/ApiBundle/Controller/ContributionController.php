<?php

namespace ApiBundle\Controller;

use Biblys\Contributor\Contributor;
use Biblys\Contributor\Job;
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
     * @throws AuthException
     * @throws PropelException
     * @throws \Biblys\Contributor\UnknownJobException
     */
    public function add(Request $request): JsonResponse
    {
        self::authAdmin($request);

        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);

        $contribution = new Role();
        $contribution->setArticleId($params["article_id"]);
        $contribution->setPeopleId($params["people_id"]);
        $contribution->setJobId($params["job_id"]);
        $contribution->save();

        $contributor = new Contributor(
            $contribution->getPeople(),
            Job::getById($contribution->getJobId()),
            $contribution->getId()
        );

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse([
            "contribution_id" => $contribution->getId(),
            "contributor_name" => $contributor->getName(),
            "contributor_role" => $contributor->getRole(),
            "authors" => $authorNamesAsString
        ]);
    }

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

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        self::authAdmin($request);

        $contribution = RoleQuery::create()->findPk($id);
        $contribution->delete();

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse(["authors" => $authorNamesAsString]);
    }

    /**
     * @param Role $contribution
     * @return string
     * @throws PropelException
     */
    private function _getAuthorNamesAsString(Role $contribution): string
    {
        $article = $contribution->getArticle();
        $contributionsByAuthors = RoleQuery::create()
            ->filterByArticle($article)
            ->filterByJobId(1)
            ->find();
        $authorNames = array_map(function (Role $contribution) {
            return $contribution->getPeople()->getName();
        }, $contributionsByAuthors->getData());

        return join(", ", $authorNames);
    }
}
