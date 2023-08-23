<?php

namespace Biblys\Service;

use Biblys\Exception\GleephAPIException;
use Biblys\Gleeph\GleephAPI;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;

require_once __DIR__."/../../setUp.php";

class GleephServiceTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws ClientExceptionInterface
     */
    public function testGetSimilarArticlesByEan()
    {
        // given
        $sitePublisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher();
        ModelFactory::createArticle(title: "A similar article", ean: "9789876543210", publisher: $sitePublisher);
        ModelFactory::createArticle(title: "An article from another site", ean: "9788765432109", publisher: $otherPublisher);
        $api = $this->createMock(GleephAPI::class);
        $api
            ->method("getSimilarBooksByEan")
            ->with("978123456789", 5)
            ->willReturn(["9788765432109", "9789876543210"]);
        $logger = $this->createMock(LoggerService::class);
        $logger->expects($this->once())->method("log")->with(
            "gleeph",
            "info",
            "Found 1 similar article(s) for EAN 978123456789",
            ["9788765432109", "9789876543210"]
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->willReturn((string) $sitePublisher->getId());
        $gleeph = new GleephService($api, $currentSite, $logger);

        // when
        $articles = $gleeph->getSimilarArticlesByEan("978123456789", 5);

        // then
        $this->assertCount(1, $articles);
        $this->assertEquals(
            "A similar article",
            $articles[0]->getTitle(),
            "returns similar article for ean",
        );
    }


    /**
     * @throws PropelException
     * @throws ClientExceptionInterface
     */
    public function testGetSimilarArticlesByEanWithAPIException()
    {
        // given
        $api = $this->createMock(GleephAPI::class);
        $api
            ->method("getSimilarBooksByEan")
            ->willThrowException(new GleephAPIException("API key is required"));
        $currentSite = $this->createMock(CurrentSite::class);
        $logger = $this->createMock(LoggerService::class);
        $logger->expects($this->once())->method("log")->with(
            "gleeph",
            "error",
            "Call to Gleeph API failed",
            ["API key is required"]
        );
        $gleeph = new GleephService($api, $currentSite, $logger);

        // when
        $articles = $gleeph->getSimilarArticlesByEan("978123456789");

        // then
        $this->assertCount(0, $articles);
    }
}
