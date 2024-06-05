<?php

namespace Model;

use Biblys\Article\Type;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setup.php";

class CartTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testContainsDownloadableArticlesReturnsFalse()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);

        // when
        $contains = $cart->containsDownloadableArticles();

        // then
        $this->assertFalse($contains);
    }

    /**
     * @throws PropelException
     */
    public function testContainsDownloadableArticlesReturnsTrue()
    {
        // given
        $cart = ModelFactory::createCart();
        $physicalArticle = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $physicalArticle, cart: $cart);
        $downloadableArticle = ModelFactory::createArticle(typeId: Type::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, cart: $cart);

        // when
        $contains = $cart->containsDownloadableArticles();

        // then
        $this->assertTrue($contains);
    }
}
