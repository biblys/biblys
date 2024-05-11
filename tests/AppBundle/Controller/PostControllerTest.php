<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\Post;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class PostControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testAnonymousUserCanSeePublishedPost()
    {
        // given
        $controller = new PostController();

        $post = ModelFactory::createPost();

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator) = $this->_buildDependencies($post);

        // when
        $response = $controller->showAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $urlGenerator,
            $post->getUrl(),
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testAnonymousUserCannotSeeOfflinePost()
    {
        // given
        $controller = new PostController();

        $post = ModelFactory::createPost(
            title: "Hors ligne",
            status: Post::STATUS_OFFLINE
        );

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator) = $this->_buildDependencies($post);

        // then
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Post hors-ligne is currently offline.");

        // when
        $controller->showAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $urlGenerator,
            $post->getUrl(),
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testAnonymousUserCannotSeeFuturePost()
    {
        // given
        $controller = new PostController();

        $post = ModelFactory::createPost(
            title: "À paraître",
            date: new \DateTime("tomorrow"),
        );

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator) = $this->_buildDependencies($post);

        // then
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Post a-paraitre is to be published.");

        // when
        $controller->showAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $urlGenerator,
            $post->getUrl(),
        );
    }

    /**
     * @param Post $post
     * @return array
     * @throws PropelException
     */
    private function _buildDependencies(Post $post): array
    {
        $request = new Request();
        $currentSite = new CurrentSite($post->getSite());
        $currentUser = new CurrentUser(null, "token");
        $templateService = Mockery::mock(TemplateService::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService->shouldReceive("renderResponse")
            ->withSomeOfArgs("AppBundle:Post:show.html.twig")
            ->andReturn(new Response());
        $urlGenerator->shouldReceive("generate")
            ->withArgs(["post_show", ["slug" => $post->getUrl()]]);
        return [$request, $currentSite, $currentUser, $templateService, $urlGenerator];
    }
}
