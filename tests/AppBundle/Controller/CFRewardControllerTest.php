<?php

namespace AppBundle\Controller;

use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class CFRewardControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws AuthException
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

        // when
        $response = $controller->newAction($request, $urlGenerator, $reward->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @throws PropelException
     * @throws AuthException
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

        // when
        $response = $controller->editAction($request, $urlGenerator, $reward->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
    }
}
