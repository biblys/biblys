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


namespace ApiBundle\Controller;

use Biblys\Contributor\Contributor;
use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Biblys\Service\CurrentUser;
use Framework\Controller;
use Model\ArticleQuery;
use Model\PublisherQuery;
use Model\Role;
use Model\RoleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContributionController extends Controller
{
    /**
     * @route GET /api/admin/articles/{articleId}/contributions
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function index(CurrentUser $currentUser, $articleId): JsonResponse
    {
        $article = ArticleQuery::create()->findPk($articleId);
        $articlePublisher = PublisherQuery::create()->findPk($article->getPublisherId());

        $currentUser->authPublisher($articlePublisher);

        $contributions = $article->getRolesJoinPeople();
        $contributors = array_map(function(Role $contribution) {
            $contributor = new Contributor(
                $contribution->getPeople(),
                Job::getById($contribution->getJobId()),
                $contribution->getId()
            );

            $gender = $contribution->getPeople()->getGender();
            $jobsForGender = $this->_getJobsForGender($gender);

            return [
                "contribution_id" => $contributor->getContributionId(),
                "contributor_name" => $contributor->getName(),
                "contributor_role" => $contributor->getRole(),
                "contributor_job_id" => $contributor->getJobId(),
                "job_options" => $jobsForGender,
            ];
        }, $contributions->getData());


        return new JsonResponse([
            "contributors" => $contributors,
        ]);
    }

    /**
     * @route POST /api/admin/articles/{articleId}/contributions
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function create(Request $request, CurrentUser $currentUser, int $articleId): JsonResponse
    {
        $article = ArticleQuery::create()->findPk($articleId);
        $articlePublisher = PublisherQuery::create()->findPk($article->getPublisherId());
        $currentUser->authPublisher($articlePublisher);

        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);

        $contribution = new Role();
        $contribution->setArticleId($articleId);
        $contribution->setPeopleId($params["people_id"]);
        $contribution->setJobId($params["job_id"]);
        $contribution->save();

        $contributor = new Contributor(
            $contribution->getPeople(),
            Job::getById($contribution->getJobId()),
            $contribution->getId()
        );

        $gender = $contribution->getPeople()->getGender();
        $jobsForGender = $this->_getJobsForGender($gender);

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse([
            "contributor" => [
                "contribution_id" => $contributor->getContributionId(),
                "contributor_name" => $contributor->getName(),
                "contributor_role" => $contributor->getRole(),
                "contributor_job_id" => $contributor->getJobId(),
                "job_options" => $jobsForGender,
            ],
            "authors" => $authorNamesAsString
        ]);
    }

    /**
     * @route PUT /api/admin/articles/{articleId}/contributions/{id}
     * @throws PropelException
     */
    public function update(Request $request, CurrentUser $currentUser, int $id): JsonResponse
    {
        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);
        $jobId = $params["job_id"];

        $contribution = RoleQuery::create()->findPk($id);

        $articlePublisher = PublisherQuery::create()->findPk($contribution->getArticle()->getPublisherId());
        $currentUser->authPublisher($articlePublisher);

        $contribution->setJobId($jobId);
        $contribution->save();

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse(["authors" => $authorNamesAsString]);
    }

    /**
     * @route DELETE /api/admin/articles/{articleId}/contributions/{id}
     * @throws PropelException
     */
    public function delete(CurrentUser $currentUser, int $id): JsonResponse
    {
        $contribution = RoleQuery::create()->findPk($id);

        $articlePublisher = PublisherQuery::create()->findPk($contribution->getArticle()->getPublisherId());
        $currentUser->authPublisher($articlePublisher);

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

    /**
     * @param string|null $gender
     * @return array|array[]
     */
    private function _getJobsForGender(?string $gender): array
    {
        $jobs = Job::getAll();
        return array_map(function (Job $job) use ($gender) {

            if ($gender === "F") {
                $jobName = $job->getFeminineName();
            } elseif ($gender === "M") {
                $jobName = $job->getMasculineName();
            } else {
                $jobName = $job->getNeutralName();
            }

            return [
                "job_id" => $job->getId(),
                "job_name" => $jobName,
            ];
        }, $jobs);
    }
}
