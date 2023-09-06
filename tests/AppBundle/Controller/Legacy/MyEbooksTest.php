<?php

namespace AppBundle\Controller\Legacy;

use Biblys\Article\Type;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
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

        $axysAccount = ModelFactory::createAxysAccount();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getAxysAccount")->andReturn($axysAccount);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);

        $urlgenerator = Mockery::mock(UrlGenerator::class);

        $article = ModelFactory::createArticle(title: "In my library", typeId: Type::EBOOK);
        ModelFactory::createStockItem(
            site: $currentSite->getSite(),
            article: $article,
            axysAccount: $axysAccount,
            sellingDate: new DateTime(),
        );

        // when
        $response = $controller($request, $urlgenerator, $currentSite, $currentUser);

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
