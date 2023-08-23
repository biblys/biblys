<?php

namespace Model;

use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleTest extends TestCase
{
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
}
