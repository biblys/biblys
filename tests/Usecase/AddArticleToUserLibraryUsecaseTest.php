<?php

namespace Usecase;

use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require_once __DIR__ . "/../setUp.php";

class AddArticleToUserLibraryUsecaseTest extends TestCase
{
    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testAddingArticleNotFromDownloadablePublishers()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $publisher = ModelFactory::createPublisher(name: "Interdit");
        $currentSite->setOption("downloadable_publishers", "");
        $user = ModelFactory::createUser(site: $site);
        $article = ModelFactory::createArticle(publisher: $publisher);
        $mailer = Mockery::mock(Mailer::class);
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage(
            "Les articles de l'éditeur Interdit ne peuvent pas être téléchargés sur ce site."
        );

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, article: $article);
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testAddingNonDownloadableArticle()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(title: "Paper book", publisher: $publisher);
        $mailer = Mockery::mock(Mailer::class);
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("L'article Paper book n'est pas téléchargeable.");

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, article: $article);
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testArticleAlreadyInLibraryAreIgnored()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(
            title: "Already", typeId: ArticleType::EBOOK, publisher: $publisher
        );
        ModelFactory::createStockItem(site: $site, article: $article, user: $user);
        $mailer = Mockery::mock(Mailer::class);
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, article: $article);

        // then
        $libraryItemsForArticle = StockQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->filterByArticle($article)
            ->count();
        $this->assertEquals(1, $libraryItemsForArticle);
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testSuccessCaseForArticle()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $publisher);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send");
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, article: $article);

        // then
        $libraryItem = StockQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->filterByArticle($article)
            ->findOne();
        $this->assertNotNull($libraryItem);
        $this->assertFalse($libraryItem->getAllowPredownload());
        $this->assertInstanceOf(DateTime::class, $libraryItem->getSellingDate());
        $mailer->shouldNotHaveReceived("send");
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testItemAlreadyInALibrary()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $otherUser = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(title: 'Already', typeId: ArticleType::EBOOK, publisher:
            $publisher);
        $item = ModelFactory::createStockItem(site: $site, article: $article, user: $otherUser);
        $mailer = Mockery::mock(Mailer::class);
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // then
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage("L'exemplaire {$item->getId()} est déjà dans une bibliothèque.");

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, items: [$item]);
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testSuccessCaseForItems()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article1 = ModelFactory::createArticle(title: 'Ebook 1', typeId: ArticleType::EBOOK, publisher: $publisher);
        $item1 = ModelFactory::createStockItem(site: $site, article: $article1);
        $article2 = ModelFactory::createArticle(title: 'Ebook 2', typeId: ArticleType::EAUDIOBOOK, publisher: $publisher);
        $item2 = ModelFactory::createStockItem(site: $site, article: $article2);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send");
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // when
        $usecase->execute(currentSite: $currentSite, user: $user, items: [$item1, $item2]);

        // then
        $item1->reload();
        $this->assertEquals($user, $item1->getUser());
        $this->assertInstanceOf(DateTime::class, $item1->getSellingDate());
        $this->assertFalse($item1->getAllowPredownload());

        $item2->reload();
        $this->assertEquals($user, $item2->getUser());
        $this->assertInstanceOf(DateTime::class, $item2->getSellingDate());
        $this->assertFalse($item2->getAllowPredownload());
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     */
    public function testSuccessCaseWhenAllowingPreDownload()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $publisher);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send");
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // when
        $usecase->execute(
            currentSite: $currentSite,
            user: $user,
            article: $article,
            allowsPreDownload: true
        );

        // then
        $libraryItem = StockQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->filterByArticle($article)
            ->findOne();
        $this->assertNotNull($libraryItem);
        $this->assertTrue($libraryItem->getAllowPredownload());
        $this->assertInstanceOf(DateTime::class, $libraryItem->getSellingDate());
    }

    /**
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testSuccessCaseForArticleWithEmailSending()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site);
        $publisher = ModelFactory::createPublisher(name: "Autorisé");
        $currentSite->setOption("downloadable_publishers", $publisher->getId());
        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK, publisher: $publisher);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send");
        $usecase = new AddArticleToUserLibraryUsecase($mailer);

        // when
        $usecase->execute(
            currentSite: $currentSite,
            user: $user,
            article: $article,
            sendEmail: true,
        );

        // then
        $mailer->shouldHaveReceived("send");
        $this->expectNotToPerformAssertions();
    }
}
