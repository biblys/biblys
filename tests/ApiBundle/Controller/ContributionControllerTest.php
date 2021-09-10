<?php

namespace ApiBundle\Controller;

use Biblys\Test\Factory;
use Exception;
use Framework\Exception\AuthException;
use Model\Role;
use Model\RoleQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class ContributionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws AuthException
     * @throws Exception
     */
    public function testAddAction()
    {
        // given
        $article = Factory::createArticle();
        $person = Factory::createPeople(["people_first_name" => "Lili", "people_last_name" => "Raton"]);
        $content = json_encode([
            "article_id" => $article->get("id"),
            "people_id" => $person->get("id"),
            "job_id" => 3,
        ]);
        $request = Factory::createAuthRequestForAdminUser($content);
        $controller = new ContributionController();

        // when
        $response = $controller->add($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $content = json_decode($response->getContent(), true);
        $updatedContribution = RoleQuery::create()->findPk($content["contribution_id"]);
        $this->assertNotNull(
            $updatedContribution,
            "it should have added the contribution"
        );
        $this->assertEquals(
            "Lili RATON",
            $content["contributor_name"],
            "it should include name in response"
        );
        $this->assertEquals(
            "Traductrice",
            $content["contributor_role"],
            "it should include role in response"
        );
        $this->assertEquals(
            "Hervé LE TERRIER",
            $content["authors"],
            "it should include authors in response"
        );
    }

    /**
     * @throws PropelException
     * @throws AuthException
     * @throws Exception
     */
    public function testUpdateAction()
    {
        // given
        $article = Factory::createArticle();
        $content = '{"job_id":"2"}';
        $request = Factory::createAuthRequestForAdminUser($content);
        $contribution = new Role();
        $contribution->setArticleId($article->get("id"));
        $contribution->setJobId(1);
        $contribution->save();
        $controller = new ContributionController();

        // when
        $response = $controller->update($request, $contribution->getId());

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
     * @throws AuthException
     * @throws Exception
     */
    public function testDeleteAction()
    {
        // given
        $article = Factory::createArticle();
        $request = Factory::createAuthRequestForAdminUser();
        $contribution = new Role();
        $contribution->setArticleId($article->get("id"));
        $contribution->setJobId(1);
        $contribution->save();
        $controller = new ContributionController();

        // when
        $response = $controller->delete($request, $contribution->getId());

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
