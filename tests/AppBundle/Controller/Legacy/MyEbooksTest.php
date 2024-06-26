<?php

namespace AppBundle\Controller\Legacy;

use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";


class MyEbooksTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testDisplaysArticlesInLibrary()
    {
        // given
        $controller = require __DIR__."/../../../../controllers/common/php/log_myebooks.php";

        $request = new Request();

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);

        $urlgenerator = Mockery::mock(UrlGenerator::class);

        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(
            new Response("In my library")
        );

        $article = ModelFactory::createArticle(title: "In my library", typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(
            site: $currentSite->getSite(),
            article: $article,
            user: $user,
            sellingDate: new DateTime(),
        );

        // when
        $response = $controller($request, $urlgenerator, $currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "In my library",
            $response->getContent(),
            "displays the article title"
        );
    }
}
