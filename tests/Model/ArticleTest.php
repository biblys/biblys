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
        ModelFactory::createStockItem([], $site, $article);
        ModelFactory::createStockItem([], $otherSite, $article);
        ModelFactory::createStockItem(["selling_date" => new DateTime()], $site, $article);
        ModelFactory::createStockItem(["return_date" => new DateTime()], $site, $article);
        ModelFactory::createStockItem(["lost_date" => new DateTime()], $site, $article);

        // when
        $count = $article->countAvailableStockItemsForSite($site);

        // then
        $this->assertEquals(1, $count);
    }
}
