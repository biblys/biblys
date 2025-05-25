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


namespace AppBundle\Controller;

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\Post;
use Model\PostQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class PostControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PostQuery::create()->deleteAll();
    }

    /** showAction */

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

        list($request, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

        // when
        $response = $controller->showAction(
            $request,
            $currentUser,
            $templateService,
            $urlGenerator,
            $imagesService,
            $metaTagsService,
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

        list($request, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

        // then
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Post hors-ligne is currently offline.");

        // when
        $controller->showAction(
            $request,
            $currentUser,
            $templateService,
            $urlGenerator,
            $imagesService,
            $metaTagsService,
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
            date: new DateTime("tomorrow"),
        );

        list($request, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

        // then
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Post a-paraitre is to be published.");

        // when
        $controller->showAction(
            $request,
            $currentUser,
            $templateService,
            $urlGenerator,
            $imagesService,
            $metaTagsService,
            $post->getUrl(),
        );
    }

    private function _buildDependencies(Post $post): array
    {
        $request = new Request();
        $currentUser = new CurrentUser(null, "token");
        $templateService = Mockery::mock(TemplateService::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService->shouldReceive("renderResponse")
            ->withSomeOfArgs("AppBundle:Post:show.html.twig")
            ->andReturn(new Response());
        $urlGenerator->shouldReceive("generate")
            ->withArgs(["post_show", ["slug" => $post->getUrl()]]);
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->shouldReceive("imageExistsFor");
        $metaTagsService = Mockery::mock(MetaTagsService::class);
        $metaTagsService->shouldReceive("setTitle");
        $metaTagsService->shouldReceive("setDescription");
        $metaTagsService->shouldReceive("setUrl");
        return [$request, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService];
    }

    /** adminAction */

    /**
     * @throws Exception
     */
    public function testAdminAction()
    {
        // given
        $controller = new PostController();
        ModelFactory::createPost(title: "Un billet de blog");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("isAdmin")->andReturn(true);
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse");
        $queryParamsService->expects("getInteger")->with("category_id")->andReturn(0);
        $queryParamsService->expects("getInteger")->with("p")->andReturn(0);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->adminAction($currentUser, $queryParamsService, $templateService);

        // then
        $this->assertStringContainsString("Un billet de blog", $response->getContent());
    }


    /** newAction */

    /**
     * @throws Exception
     */
    public function testNewAction()
    {
        // given
        $controller = new PostController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("getUser")->andReturn(ModelFactory::createUser());
        $currentUser->expects("hasPublisherRight")->andReturn(false);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->newAction($currentUser, $templateService);

        // then
        $this->assertStringContainsString("Nouveau billet", $response->getContent());
    }


    /** createAction */

    /**
     * @throws Exception
     */
    public function testCreateAction()
    {
        // given
        $controller = new PostController();

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse");
        $bodyParams->expects("getInteger")->with("user_id")->andReturn(1);
        $bodyParams->expects("getInteger")->with("publisher_id")->andReturn(2);
        $bodyParams->expects("get")->with("post_title")->andReturn("Un billet de blog");
        $bodyParams->expects("get")->with("post_excerpt")->andReturn("Extrait du billet");
        $bodyParams->expects("get")->with("post_content")->andReturn("Contenu du billet");
        $bodyParams->expects("getInteger")->with("category_id")->andReturn(3);
        $bodyParams->expects("get")->with("post_link")->andReturn("https://example.net");
        $bodyParams->expects("get")->with("post_illustration_legend")->andReturn("Légende de l'illustration");
        $bodyParams->expects("getBoolean")->with("post_status")->andReturn(true);
        $bodyParams->expects("getBoolean")->with("post_selected")->andReturn(false);
        $bodyParams->expects("get")->with("post_date")->andReturn("2019-04-28 02:42");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("getUser")->andReturn(ModelFactory::createUser());

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("/blog/1");

        $imagesService = Mockery::mock(ImagesService::class);

        // when
        $response = $controller->createAction($bodyParams, $currentUser, $urlGenerator, $imagesService);

        // then
        $this->assertEquals("/blog/1", $response->getTargetUrl());

        $post = PostQuery::create()->findOneByTitle("Un billet de blog");
        $this->assertEquals("Un billet de blog", $post->getTitle());
        $this->assertEquals("Extrait du billet", $post->getExcerpt());
        $this->assertEquals("Contenu du billet", $post->getContent());
        $this->assertEquals(3, $post->getCategoryId());
        $this->assertEquals("https://example.net", $post->getLink());
        $this->assertEquals("Légende de l'illustration", $post->getIllustrationLegend());
        $this->assertEquals(1, $post->getUserId());
        $this->assertEquals(2, $post->getPublisherId());
        $this->assertEquals(Post::STATUS_ONLINE, $post->getStatus());
        $this->assertEquals("2019-04-28 02:42:00", $post->getDate()->format("Y-m-d H:i:s"));
        $this->assertEquals(0, $post->getSelected());
    }


    /** editAction */

    /**
     * @throws Exception
     */
    public function testEditAction()
    {
        // given
        $controller = new PostController();
        $post = ModelFactory::createPost(title: "Un billet de blog à modifier");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->editAction($currentUser, $templateService, $post->getId());

        // then
        $this->assertStringContainsString("Un billet de blog à modifier", $response->getContent());
    }


    /** updateAction */

    /**
     * @throws Exception
     */
    public function testUpdateAction()
    {
        // given
        $controller = new PostController();
        $post = ModelFactory::createPost();

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->expects("parse");
        $bodyParams->expects("getInteger")->with("user_id")->andReturn(1);
        $bodyParams->expects("getInteger")->with("publisher_id")->andReturn(2);
        $bodyParams->expects("get")->with("post_title")->andReturn("Un billet de blog");
        $bodyParams->expects("get")->with("post_excerpt")->andReturn("Extrait du billet");
        $bodyParams->expects("get")->with("post_content")->andReturn("Contenu du billet");
        $bodyParams->expects("getInteger")->with("category_id")->andReturn(3);
        $bodyParams->expects("get")->with("post_link")->andReturn("https://example.net");
        $bodyParams->expects("get")->with("post_illustration_legend")->andReturn("Légende de l'illustration");
        $bodyParams->expects("getBoolean")->with("post_status")->andReturn(true);
        $bodyParams->expects("getBoolean")->with("post_selected")->andReturn(false);
        $bodyParams->expects("get")->with("post_date")->andReturn("2019-04-28 02:42");


        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authPublisher");
        $currentUser->expects("getUser")->andReturn(ModelFactory::createUser());

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("/blog/1");

        $imagesService = Mockery::mock(ImagesService::class);

        // when
        $response = $controller->updateAction($bodyParams, $currentUser, $urlGenerator, $imagesService, $post->getId());

        // then
        $this->assertEquals("/blog/1", $response->getTargetUrl());

        $post = PostQuery::create()->findOneByTitle("Un billet de blog");
        $this->assertEquals("Un billet de blog", $post->getTitle());
        $this->assertEquals("Extrait du billet", $post->getExcerpt());
        $this->assertEquals("Contenu du billet", $post->getContent());
        $this->assertEquals(3, $post->getCategoryId());
        $this->assertEquals("https://example.net", $post->getLink());
        $this->assertEquals("Légende de l'illustration", $post->getIllustrationLegend());
        $this->assertEquals(1, $post->getUserId());
        $this->assertEquals(2, $post->getPublisherId());
        $this->assertEquals(Post::STATUS_ONLINE, $post->getStatus());
        $this->assertEquals("2019-04-28 02:42:00", $post->getDate()->format("Y-m-d H:i:s"));
        $this->assertEquals(0, $post->getSelected());
    }
}
