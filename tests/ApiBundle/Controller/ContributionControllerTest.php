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
}
