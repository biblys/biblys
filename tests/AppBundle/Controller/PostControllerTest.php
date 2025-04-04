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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use DateTime;
use Mockery;
use Model\Post;
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

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

        // when
        $response = $controller->showAction(
            $request,
            $currentSite,
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

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

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

        list($request, $currentSite, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService) = $this->_buildDependencies($post);

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
            $imagesService,
            $metaTagsService,
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
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->shouldReceive("imageExistsFor");
        $metaTagsService = Mockery::mock(MetaTagsService::class);
        $metaTagsService->shouldReceive("setTitle");
        $metaTagsService->shouldReceive("setDescription");
        $metaTagsService->shouldReceive("setUrl");
        return [$request, $currentSite, $currentUser, $templateService, $urlGenerator, $imagesService, $metaTagsService];
    }
}
