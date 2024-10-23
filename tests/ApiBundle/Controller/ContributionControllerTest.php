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

use Biblys\Service\CurrentUser;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use Model\Role;
use Model\RoleQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class ContributionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction()
    {
        // given
        $person = EntityFactory::createPeople(["people_first_name" => "Lili", "people_last_name" => "Raton"]);
        $article = EntityFactory::createArticle([], []);
        $contribution = new Role();
        $contribution->setArticleId($article->get("id"));
        $contribution->setPeopleId($person->get("id"));
        $contribution->setJobId(1);
        $contribution->save();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher")->once()->andReturn(true);
        $controller = new ContributionController();

        // when
        $response = $controller->index($currentUser, $article->get("id"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $content = json_decode($response->getContent(), true);
        $contributor1 = $content["contributors"][0];
        $this->assertEquals(
            $contribution->getId(),
            $contributor1["contribution_id"],
            "it should include contribution id in response"
        );
        $this->assertEquals(
            "Lili RATON",
            $contributor1["contributor_name"],
            "it should include name in response"
        );
        $this->assertEquals(
            "Autrice",
            $contributor1["contributor_role"],
            "it should include role in response"
        );
        $this->assertEquals(
            1,
            $contributor1["contributor_job_id"],
            "it should include job id in response"
        );
        $this->assertEquals(
            ["job_id" => 1, "job_name" => "Autrice"],
            $contributor1["job_options"][2],
            "it should include jobs with correct gender"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateAction()
    {
        // given
        $article = EntityFactory::createArticle();
        $person = EntityFactory::createPeople(["people_first_name" => "Lili", "people_last_name" => "Raton"]);
        $content = json_encode([
            "people_id" => $person->get("id"),
            "job_id" => 14,
        ]);
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher")->once()->andReturn(true);
        $controller = new ContributionController();

        // when
        $response = $controller->create($request, $currentUser, $article->get("id"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $content = json_decode($response->getContent(), true);
        $contributor = $content["contributor"];
        $addedContribution = RoleQuery::create()->findPk($contributor["contribution_id"]);
        $this->assertNotNull(
            $addedContribution,
            "it should have added the contribution"
        );
        $this->assertEquals(
            $addedContribution->getId(),
            $contributor["contribution_id"],
            "it should include contribution id in response"
        );
        $this->assertEquals(
            "Lili RATON",
            $contributor["contributor_name"],
            "it should include name in response"
        );
        $this->assertEquals(
            "Autrice de la postface",
            $contributor["contributor_role"],
            "it should include role in response"
        );
        $this->assertEquals(
            14,
            $contributor["contributor_job_id"],
            "it should include job id in response"
        );
        $this->assertEquals(
            ["job_id" => 1, "job_name" => "Autrice"],
            $contributor["job_options"][2],
            "it should include jobs with correct gender"
        );
        $this->assertEquals(
            "Hervé LE TERRIER",
            $content["authors"],
            "it should include authors in response"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testUpdateAction()
    {
        // given
        $article = EntityFactory::createArticle();
        $content = '{"job_id":"2"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $contribution = new Role();
        $contribution->setArticleId($article->get("id"));
        $contribution->setPeople(ModelFactory::createPeople());
        $contribution->setJobId(1);
        $contribution->save();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher")->once()->andReturn(true);
        $controller = new ContributionController();

        // when
        $response = $controller->update($request, $currentUser, $contribution->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $updatedContribution = RoleQuery::create()->findPk($contribution->getId());
        $this->assertEquals(
            2,
            $updatedContribution->getJobId(),
            "it should have updated job id"
        );
        $this->assertEquals(
            "{\"authors\":\"Herv\u00e9 LE TERRIER\"}",
            $response->getContent(),
            "it should response with authors"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteAction()
    {
        // given
        $article = EntityFactory::createArticle();
        $contribution = new Role();
        $contribution->setArticleId($article->get("id"));
        $contribution->setPeople(ModelFactory::createPeople());
        $contribution->setJobId(1);
        $contribution->save();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authPublisher")->once()->andReturn(true);
        $controller = new ContributionController();

        // when
        $response = $controller->delete($currentUser, $contribution->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $deletedContribution = RoleQuery::create()->findPk($contribution->getId());
        $this->assertNull(
            $deletedContribution,
            "it should have deleted the contribution"
        );
        $this->assertEquals(
            "{\"authors\":\"Herv\u00e9 LE TERRIER\"}",
            $response->getContent(),
            "it should response with authors"
        );
    }
}
