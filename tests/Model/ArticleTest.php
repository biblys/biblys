<?php

namespace Model;

use Biblys\Exception\CannotDeleteArticleWithStock;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleTest extends TestCase
{
    /** Article->isPublished */

    /**
     * @throws PropelException
     */
    public function testIsPublishedReturnsTrueForPastDate()
    {
        // given
        $article = new Article();
        $article->setPubdate("2019-04-28");

        // when
        $isPublished = $article->isPublished();

        // then
        $this->assertTrue($isPublished);
    }

    public function testIsPublishedReturnsTrueForPresentDate()
    {
        // given
        $article = new Article();
        $article->setPubdate(date("Y-m-d"));

        // when
        $isPublished = $article->isPublished();

        // then
        $this->assertTrue($isPublished);
    }

    public function testIsPublishedReturnsTrueForFutureDate()
    {
        // given
        $article = new Article();
        $article->setPubdate("2099-04-28");

        // when
        $isPublished = $article->isPublished();

        // then
        $this->assertFalse($isPublished);
    }

    /**
     * @throws Exception
     */
    public function testAddContributor()
    {
        // given
        $article = new Article();
        $contributor = ModelFactory::createPeople();
        $job = \Biblys\Contributor\Job::getById(\Biblys\Contributor\Job::AUTHOR);

        // when
        $article->addContributor($contributor, $job);

        // then
        $role = RoleQuery::create()
            ->filterByArticleId($article->getId())
            ->filterByPeopleId($contributor->getId())
            ->filterByJobId(\Biblys\Contributor\Job::AUTHOR)
            ->findOne();
        $this->assertNotNull($role, "role should have been created");
    }

    /**
     * @throws PropelException
     */
    public function testCountAvailableStockForSite()
    {
        // given
        $site = ModelFactory::createSite();
        $otherSite = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(site: $site, article: $article);
        ModelFactory::createStockItem(site: $otherSite, article: $article);
        ModelFactory::createStockItem(site: $site, article: $article, sellingDate: new DateTime());
        ModelFactory::createStockItem(site: $site, article: $article, returnDate: new DateTime());
        ModelFactory::createStockItem(site: $site, article: $article, lostDate: new DateTime());

        // when
        $count = $article->countAvailableStockItemsForSite($site);

        // then
        $this->assertEquals(1, $count);
    }

    /**
     * #isWatermarkable
     */

    public function testIsWatermarkableWithMasterIdReturnsTrue(): void
    {
        // given
        $article = new Article();
        $article->setLemoninkMasterId("1234");

        // when
        $isWatermarkable = $article->isWatermarkable();

        // then
        $this->assertTrue($isWatermarkable);
    }

    public function testIsWatermarkableWithoutMasterIdReturnsFalse(): void
    {
        // given
        $article = new Article();
        $article->setLemoninkMasterId(null);

        // when
        $isWatermarkable = $article->isWatermarkable();

        // then
        $this->assertFalse($isWatermarkable);
    }

    public function testDeleteSucceedsIfArticleHasNoStock(): void
    {
        // given
        $article = ModelFactory::createArticle();

        // when
        $article->delete();

        // then
        $this->assertTrue($article->isDeleted());
    }

    public function testDeleteIsImposibleIfArticleHasStock(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article);

        // when
        $thrownException = null;
        try {
            $article->delete();
        } catch (CannotDeleteArticleWithStock $exception) {
            $thrownException = $exception;
        }

        // then
        $this->assertInstanceOf(CannotDeleteArticleWithStock::class, $thrownException);
    }
}
