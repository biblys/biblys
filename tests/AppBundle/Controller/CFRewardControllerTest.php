<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class CFRewardControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testNewAction()
    {
        // given
        $controller = new CFRewardController();
        $reward = ModelFactory::createCrowdfundingReward();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->setMethod("POST");
        $request->request->set("content", $reward->getContent());
        $request->request->set("articles", $reward->getArticles());
        $request->request->set("image", $reward->getImage());
        $request->request->set("limited", $reward->getLimited());
        $request->request->set("highlighted", $reward->getHighlighted());
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $currentUser = Mockery::mock(CurrentUser::class);

        // when
        $response = $controller->newAction(
            $request,
            $urlGenerator,
            $currentUser,
            $reward->getId()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testNewActionWithUnexistingArticles()
    {
        // given
        $controller = new CFRewardController();
        $reward = ModelFactory::createCrowdfundingReward();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->setMethod("POST");
        $request->request->set("content", $reward->getContent());
        $request->request->set("articles", "[9999]");
        $request->request->set("image", $reward->getImage());
        $request->request->set("limited", $reward->getLimited());
        $request->request->set("highlighted", $reward->getHighlighted());
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $currentUser = Mockery::mock(CurrentUser::class);

        // then
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("L'article 9999 n'existe pas");

        // when
        $controller->newAction(
            $request,
            $urlGenerator,
            $currentUser,
            $reward->getCampaignId(),
        );
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testEditAction()
    {
        // given
        $controller = new CFRewardController();
        $reward = ModelFactory::createCrowdfundingReward();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->setMethod("POST");
        $request->request->set("content", $reward->getContent());
        $request->request->set("articles", $reward->getArticles());
        $request->request->set("image", $reward->getImage());
        $request->request->set("limited", $reward->getLimited());
        $request->request->set("highlighted", $reward->getHighlighted());
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->editAction(
            $request,
            $urlGenerator,
            $currentUser,
            $reward->getId()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testEditActionWithUnexistingArticles()
    {
        // given
        $controller = new CFRewardController();
        $reward = ModelFactory::createCrowdfundingReward();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->setMethod("POST");
        $request->request->set("content", $reward->getContent());
        $request->request->set("articles", "[9999]");
        $request->request->set("image", $reward->getImage());
        $request->request->set("limited", $reward->getLimited());
        $request->request->set("highlighted", $reward->getHighlighted());
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // then
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("L'article 9999 n'existe pas");

        // when
        $controller->editAction(
            $request,
            $urlGenerator,
            $currentUser,
            $reward->getId()
        );
    }
}
