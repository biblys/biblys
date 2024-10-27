<?php

namespace Model;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ImageQueryTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithArticle(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);

        // when
        $image = ImageQuery::create()->filterByModel($article)->findOne();

        // then
        $this->assertEquals($image->getArticle(), $article);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithStockItem(): void
    {
        // given
        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(stockItem: $stockItem);

        // when
        $image = ImageQuery::create()->filterByModel($stockItem)->findOne();

        // then
        $this->assertEquals($image->getStockItem(), $stockItem);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithPost(): void
    {
        // given
        $post = ModelFactory::createPost();
        ModelFactory::createImage(post: $post);

        // when
        $image = ImageQuery::create()->filterByModel($post)->findOne();

        // then
        $this->assertEquals($image->getPost(), $post);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithPublisher(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createImage(publisher: $publisher);

        // when
        $image = ImageQuery::create()->filterByModel($publisher)->findOne();

        // then
        $this->assertEquals($image->getPublisher(), $publisher);
    }

    /**
     * @throws PropelException
     */
    public function testFilterByModelWithContributor(): void
    {
        // given
        $contributor = ModelFactory::createContributor();
        ModelFactory::createImage(contributor: $contributor);

        // when
        $image = ImageQuery::create()->filterByModel($contributor)->findOne();

        // then
        $this->assertEquals($image->getContributor(), $contributor);
    }

}
