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


namespace Model;

use Biblys\Data\ArticleType;
use Biblys\Exception\ArticleIsUnavailableException;
use Biblys\Exception\CannotDeleteArticleWithStock;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ArticleTest extends TestCase
{
    /** ensureAvailability */

    /**
     * @throws ArticleIsUnavailableException
     */
    public function testEnsureAvailabilityIfArticleIsAvailable(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_AVAILABLE);

        // when
        $article->ensureAvailability();

        // then
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsSoonOutOfPrint(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_AVAILABLE);

        // when
        $article->ensureAvailability();

        // then
        $this->assertTrue(true);
    }


    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsUpcoming(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_UPCOMING);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("à paraître", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsToBeReprinted(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_REPRINT_IN_PROGRESS);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("en cours de réimpression", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsTemporarilyUnavailable(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_TEMPORARILY_UNAVAILABLE);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("momentanément indisponible", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsOutOfPrint(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_OUT_OF_PRINT);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("épuisé", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsToBeReissued(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_TO_BE_REISSUED);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("à reparaître", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticleIsPrivatelyPrinted(): void
    {
        // given
        $article = new Article();
        $article->setAvailabilityDilicom(Article::AVAILABILITY_PRIVATELY_PRINTED);

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("hors commerce", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticlePublicationDateIsInTheFuture(): void
    {
        // given
        $article = new Article();
        $article->setPubdate(new DateTime("tomorrow"));

        // when
        $exception = Helpers::runAndCatchException(fn() => $article->ensureAvailability());

        // then
        $this->assertInstanceOf(ArticleIsUnavailableException::class, $exception);
        $this->assertEquals("à paraître", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testEnsureAvailabilityIfArticlePublicationDateIsInTheFutureButPreorderable(): void
    {
        // given
        $article = new Article();
        $article->setPubdate(new DateTime("tomorrow"));
        $article->setPreorder(1);

        // when
        $article->ensureAvailability();

        // then
        $this->assertTrue(true);
    }


    /** isPublished */

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

    /** addContributor */

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

    /** countAvailableStockForSite */

    /**
     * @throws PropelException
     */
    public function testCountAvailableStockForSite()
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(site: $site, article: $article);
        ModelFactory::createStockItem(site: $site, article: $article, sellingDate: new DateTime());
        ModelFactory::createStockItem(site: $site, article: $article, returnDate: new DateTime());
        ModelFactory::createStockItem(site: $site, article: $article, lostDate: new DateTime());

        // when
        $count = $article->countAvailableStockItems();

        // then
        $this->assertEquals(1, $count);
    }

    /** isWatermarkable */

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

    /** isService */

    public function testIsServiceReturnsFalse(): void
    {
        // given
        $article = new Article();
        $article->setTypeId(ArticleType::BOOK);

        // when
        $isService = $article->isService();

        // then
        $this->assertFalse($isService);
    }

    public function testIsServiceReturnsTrue(): void
    {
        // given
        $article = new Article();
        $article->setTypeId(ArticleType::SUBSCRIPTION);

        // when
        $isService = $article->isService();

        // then
        $this->assertTrue($isService);
    }

    /** isBundle */

    public function testIsBundleReturnsFalse(): void
    {
        // given
        $article = new Article();
        $article->setTypeId(ArticleType::BOOK);

        // when
        $isBundle = $article->isBundle();

        // then
        $this->assertFalse($isBundle);
    }

    public function testIsBundleReturnsTrue(): void
    {
        // given
        $article = new Article();
        $article->setTypeId(ArticleType::BUNDLE);

        // when
        $isBundle = $article->isBundle();

        // then
        $this->assertTrue($isBundle);
    }

    /** getArticlesFromBundle */

    /**
     * @throws PropelException
     */

    public function testGetArticlesFromBundle(): void
    {
        // given
        $bundle = ModelFactory::createArticle();
        $articleInBundle1 = ModelFactory::createArticle();
        $articleInBundle2 = ModelFactory::createArticle();
        ModelFactory::createLink(article: $articleInBundle1, bundleArticle: $bundle);
        ModelFactory::createLink(article: $articleInBundle2, bundleArticle: $bundle);

        // when
        $articlesFromBundle = $bundle->getArticlesFromBundle();

        // then
        $this->assertContains($articleInBundle1, $articlesFromBundle);
        $this->assertContains($articleInBundle2, $articlesFromBundle);
    }

    /** isInABundle */

    /**
     * @throws PropelException
     */
    public function testIsInABundleReturnsFalse(): void
    {
        // given
        $article = ModelFactory::createArticle();

        // when
        $isInABundle = $article->isInABundle();

        // then
        $this->assertFalse($isInABundle);
    }

    /**
     * @throws PropelException
     */
    public function testIsInABundleReturnsTrue(): void
    {
        // given
        $bundle = ModelFactory::createArticle();
        $articleInBundle = ModelFactory::createArticle();
        ModelFactory::createLink(article: $articleInBundle, bundleArticle: $bundle);

        // when
        $isInABundle = $articleInBundle->isInABundle();

        // then
        $this->assertTrue($isInABundle);
    }

    /** getContainingArticleBundles */

    /**
     * @throws PropelException
     */

    public function testGetBundles(): void
    {
        // given
        $bundle = ModelFactory::createArticle();
        $articleInBundle = ModelFactory::createArticle();
        ModelFactory::createLink(article: $articleInBundle, bundleArticle: $bundle);

        // when
        $containingArticleBundles = $articleInBundle->getBundles();

        // then
        $this->assertContains($bundle, $containingArticleBundles);
    }

    /** delete */

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

    /** getCartButtonLabel */

    /**
     * @throws PropelException
     */
    public function testGetCartButtonLabelForPublishedArticle(): void
    {
        // given
        $article = new Article();

        // when
        $label = $article->getCartButtonLabel();

        // then
        $this->assertEquals("Ajouter au panier", $label);
    }

    /**
     * @throws PropelException
     */
    public function testGetCartButtonLabelForComingSoonArticle(): void
    {
        // given
        $article = new Article();
        $article->setPubdate(new DateTime("tomorrow"));

        // when
        $label = $article->getCartButtonLabel();

        // then
        $this->assertEquals("Précommander", $label);
    }

}
